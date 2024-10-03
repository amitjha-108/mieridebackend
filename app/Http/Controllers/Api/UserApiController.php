<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\User;
use App\Models\TempOtp;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserApiController extends Controller
{
    public function loginWithOtporPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'contact' => 'required',
            'password' => 'nullable|string|min:6|max:6',
            'otp' => 'nullable|string|min:6|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $user = User::where('country_code', $request->country_code)->where('contact', $request->contact)->first();

        // Reusable OTP validation logic
        $checkOtpExpiryAndMatch = function($otp, $otpModel) {
            $otpCreatedTime = Carbon::parse($otpModel->updated_at);

            // Check if OTP is expired (older than 2 minutes)
            if ($otpCreatedTime->diffInMinutes(Carbon::now()) > 2) {
                return ['status' => false, 'message' => 'OTP has expired'];
            }

            // Check if OTP matches
            if ($otp != $otpModel->otp) {
                return ['status' => false, 'message' => 'Invalid OTP'];
            }

            return ['status' => true];
        };

        // Existing user login flow
        if ($user) {
            // Check login with password
            if ($request->filled('password')) {
                if ($request->password == $user->password) {
                    if ($user->is_login) {
                        return response()->json([
                            'message' => 'User logged in successfully',
                            'status' => 'success',
                            'statusCode' => '200',
                            'is_login' => true,
                            'token' => $user->createToken('auth_token')->accessToken,
                            'data' => $user,
                        ], 200);
                    } else {
                        return response()->json([
                            'message' => 'User profile is incomplete',
                            'status' => 'success',
                            'statusCode' => '200',
                            'is_login' => false,
                            'token' => $user->createToken('auth_token')->accessToken,
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'message' => 'Invalid password',
                        'status' => 'failure',
                        'statusCode' => '200',
                    ], 200);
                }
            }

            // Handle login via OTP
            if ($request->filled('otp')) {
                $otpValidation = $checkOtpExpiryAndMatch($request->otp, $user);

                if ($otpValidation['status']) {
                    if ($user->is_login) {
                        return response()->json([
                            'message' => 'User logged in successfully',
                            'status' => 'success',
                            'statusCode' => '200',
                            'is_login' => true,
                            'token' => $user->createToken('auth_token')->accessToken,
                            'data' => $user,
                        ], 200);
                    } else {
                        return response()->json([
                            'message' => 'User profile is incomplete',
                            'status' => 'success',
                            'statusCode' => '200',
                            'is_login' => false,
                            'token' => $user->createToken('auth_token')->accessToken,
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'message' => $otpValidation['message'],
                        'status' => 'failure',
                        'statusCode' => '200',
                    ], 200);
                }
            }

            return response()->json([
                'message' => 'Please provide either password or OTP',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        // New user creation flow
        if ($request->filled('otp')) {
            $tempOtp = TempOtp::where('country_code', $request->country_code)
                ->where('contact', $request->contact)
                ->where('otp', $request->otp)
                ->where('type', 'user')
                ->first();

            if ($tempOtp) {
                $otpValidation = $checkOtpExpiryAndMatch($request->otp, $tempOtp);

                if ($otpValidation['status']) {
                    $user = new User();
                    $user->country_code = $request->country_code;
                    $user->contact = $request->contact;
                    $user->password = $request->password;
                    $user->otp = $request->otp;
                    $user->save();

                    return response()->json([
                        'message' => 'User created successfully',
                        'status' => 'success',
                        'statusCode' => '200',
                        'is_login' => false,
                        'token' => $user->createToken('auth_token')->accessToken,
                        'data' => $user,
                    ], 200);
                } else {
                    return response()->json([
                        'message' => $otpValidation['message'],
                        'status' => 'failure',
                        'statusCode' => '200',
                    ], 200);
                }
            }

            return response()->json([
                'message' => 'Invalid OTP',
                'status' => 'failure',
                'statusCode' => '200',
            ], 200);
        }

        return response()->json([
            'message' => 'Please provide an OTP for new user creation',
            'status' => 'failure',
            'statusCode' => '400',
        ], 400);
    }


    public function completeUserProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'email' => 'required|email|unique:users,email,' . auth()->guard('api')->user()->id,
            'password' => 'required|string|min:6|max:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max size
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $user = auth()->guard('api')->user();

        if ($user) {
            // Update user fields
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->gender = $request->gender;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->is_login = true;

            // Handle image upload if present
            if ($request->hasFile('image')) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }

                $image = $request->file('image');
                $imageName = 'profileImage.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('User/' . $user->id, $imageName, 'public');

                $user->image = $imagePath;
            }
            $user->save();

            return response()->json([
                'message' => 'Profile updated successfully',
                'status' => 'success',
                'statusCode' => '200',
                'data' => $user,
            ], 200);
        }
        else {
            return response()->json([
                'message' => 'User not found',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }
    }

    public function listUsers()
    {
        $users = User::all();

        return response()->json([
            'message' => 'Users retrieved successfully',
            'status' => 'success',
            'statusCode' => '200',
            'users' => $users,
        ], 200);
    }


}
