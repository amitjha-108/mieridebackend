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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('country_code', $request->country_code)->where('contact', $request->contact)->first();

        if ($user) {
            if ($request->filled('password')) {
                if ($request->password == $user->password) {
                    if ($user->is_login) {
                        return response()->json([
                            'message' => 'User logged in successfully',
                            'status' => 'success',
                            'statusCode' => '200',
                            'token' => $user->createToken('auth_token')->accessToken,
                            'data' => $user,
                        ], 200);
                    }
                    else {
                        return response()->json([
                            'message' => 'User profile is incomplete',
                            'status' => 'success',
                            'statusCode' => '200',
                            'is_login' => false,
                            'token' => $user->createToken('auth_token')->accessToken,
                        ], 200);
                    }
                }
                else {
                    return response()->json([
                        'message' => 'Invalid password',
                        'status' => 'failure',
                        'statusCode' => '200',
                    ], 200);
                }
            }

            // Handle login via OTP
            if ($request->filled('otp')) {
                if ($request->otp == $user->otp) {
                    if ($user->is_login) {
                        return response()->json([
                            'message' => 'User logged in successfully',
                            'status' => 'success',
                            'statusCode' => '200',
                            'token' => $user->createToken('auth_token')->accessToken,
                            'data' => $user,
                        ], 200);
                    }
                    else {
                        return response()->json([
                            'message' => 'User profile is incomplete',
                            'status' => 'success',
                            'statusCode' => '200',
                            'is_login' => false,
                            'token' => $user->createToken('auth_token')->accessToken,
                        ], 200);
                    }
                }
                else {
                    return response()->json([
                        'message' => 'Invalid OTP',
                        'status' => 'failure',
                        'statusCode' => '200',
                    ], 200);
                }
            }

            // If neither OTP nor password is provided
            return response()->json([
                'message' => 'Please provide either password or OTP',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        // Check OTP in TempOtp model before creating the user
        if ($request->filled('otp')) {
            $tempOtp = TempOtp::where('country_code', $request->country_code)->where('contact', $request->contact)->where('otp', $request->otp)->where('type', 'user')->first();

            if ($tempOtp) {
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
                    'token' => $user->createToken('auth_token')->accessToken,
                    'data' => $user,
                ], 200);
            }
            else {
                return response()->json([
                    'message' => 'Invalid OTP',
                    'status' => 'failure',
                    'statusCode' => '200',
                ], 200);
            }
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
            'image'      => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max size
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = auth()->guard('api')->user();

        $userData = User::where('id', $user->id)->first();

        if ($userData) {
            $userData->first_name = $request->first_name;
            $userData->last_name = $request->last_name;
            $userData->gender = $request->gender;
            $userData->email = $request->email;
            $userData->password = $request->password;
            $userData->is_login = true;
            $userData->save();

            return response()->json([
                'message' => 'Profile updated successfully',
                'status' => 'success',
                'statusCode' => '200',
                'data' => $userData,
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'User not found',
                'status' => 'failure',
                'statusCode' => '404',
            ], 404);
        }


    }



}
