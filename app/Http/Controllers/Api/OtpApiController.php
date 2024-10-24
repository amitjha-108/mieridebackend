<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\User;
use App\Models\Driver;
use App\Models\TempOtp;
use Illuminate\Support\Facades\Validator;

class OtpApiController extends Controller
{
    public function sendUserOTP(Request $request)
    {   //dd($request->all());
        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'contact' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $otp = rand(100000, 999999);
        $phone = '+' . $request->country_code . $request->contact;

        try {
            $user = User::where('country_code', $request->country_code)->where('contact', $request->contact)->first();

            if ($user) {
                $user->otp = $otp;
                $user->save();
            }
            else {
                TempOtp::create([
                    'country_code' => $request->country_code,
                    'contact' => $request->contact,
                    'otp' => $otp,
                    'type' => 'user',
                ]);
            }

            // Send OTP via Twilio
            $twilioSid = env('TWILIO_SID');
            $twilioAuthToken = env('TWILIO_AUTH_TOKEN');
            $twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');

            $twilio = new Client($twilioSid, $twilioAuthToken);

            $twilio->messages->create(
                $phone,
                [
                    'from' => $twilioPhoneNumber,
                    'body' => "Your OTP is: " . $otp
                ]
            );

            return response()->json([
                'message' => 'OTP sent successfully',
                'status' => 'success',
                'statusCode' => '200',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'status' => 'failure',
                'statusCode' => '500',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function sendDriverLoginOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'contact' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $otp = rand(100000, 999999);
        $phone = '+' . $request->country_code . $request->contact;

        try {
            $driver = Driver::where('country_code', $request->country_code)->where('contact', $request->contact)->first();

            if ($driver) {
                $driver->otp = $otp;
                $driver->save();
            }
            else {
                return response()->json([
                    'message' => 'Phone Number Not Registered',
                    'status' => 'success',
                    'statusCode' => '200',
                ], 200);
            }

            // Send OTP via Twilio
            $twilioSid = env('TWILIO_SID');
            $twilioAuthToken = env('TWILIO_AUTH_TOKEN');
            $twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');

            $twilio = new Client($twilioSid, $twilioAuthToken);

            $twilio->messages->create(
                $phone,
                [
                    'from' => $twilioPhoneNumber,
                    'body' => "Your OTP is: " . $otp
                ]
            );

            return response()->json([
                'message' => 'OTP sent successfully',
                'status' => 'success',
                'statusCode' => '200',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'status' => 'failure',
                'statusCode' => '500',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function sendDriverRegisterOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'contact' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $otp = rand(100000, 999999);
        $phone = '+' . $request->country_code . $request->contact;

        try {
            $driver = Driver::where('country_code', $request->country_code)
                            ->where('contact', $request->contact)
                            ->first();

            if ($driver) {
                return response()->json([
                    'message' => 'Phone Number Already Registered',
                    'status' => 'failure',
                    'statusCode' => '200',
                ], 200);
            }
            else {
                TempOtp::create([
                    'country_code' => $request->country_code,
                    'contact' => $request->contact,
                    'otp' => $otp,
                    'type' => 'driver',
                ]);

                // Send OTP via Twilio
                $twilioSid = env('TWILIO_SID');
                $twilioAuthToken = env('TWILIO_AUTH_TOKEN');
                $twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');

                $twilio = new Client($twilioSid, $twilioAuthToken);

                $twilio->messages->create(
                    $phone,
                    [
                        'from' => $twilioPhoneNumber,
                        'body' => "Your OTP is: " . $otp
                    ]
                );

                return response()->json([
                    'message' => 'OTP sent successfully',
                    'status' => 'success',
                    'statusCode' => '200',
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'status' => 'failure',
                'statusCode' => '500',
                'data' => $e->getMessage(),
            ], 500);
        }
    }


}
