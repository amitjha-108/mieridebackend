<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\TempOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Storage;

class DriverApiController extends Controller
{
    public function driverLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|string|max:5',
            'contact' => 'required|string|max:15',
            'password' => 'nullable|string|min:6|max:6',
            'otp' => 'nullable|string|min:6|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $driver = Driver::where('country_code', $request->country_code)->where('contact', $request->contact)->first();

        if ($driver) {
            if ($request->filled('password')) {
                if ($request->password == $driver->password) {
                    if ($driver->is_login) {
                        return response()->json([
                            'message' => 'Driver logged in successfully',
                            'status' => 'success',
                            'statusCode' => '200',
                            'token' => $driver->createToken('auth_token')->accessToken,
                            'data' => $driver,
                        ], 200);
                    }
                    else {
                        return response()->json([
                            'message' => 'Driver profile is incomplete',
                            'status' => 'success',
                            'statusCode' => '200',
                            'is_login' => false,
                            'token' => $driver->createToken('auth_token')->accessToken,
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

            if ($request->filled('otp')) {
                if ($request->otp == $driver->otp) {
                    if ($driver->is_login) {
                        return response()->json([
                            'message' => 'Driver logged in successfully',
                            'status' => 'success',
                            'statusCode' => '200',
                            'token' => $driver->createToken('auth_token')->accessToken,
                            'data' => $driver,
                        ], 200);
                    }
                    else {
                        return response()->json([
                            'message' => 'Driver profile is incomplete',
                            'status' => 'success',
                            'statusCode' => '200',
                            'is_login' => false,
                            'token' => $driver->createToken('auth_token')->accessToken,
                        ], 200);
                    }
                }
                else {
                    return response()->json([
                        'message' => 'Invalid OTP',
                        'status' => 'failure',
                        'statusCode' => '403',
                    ], 403);
                }
            }

            return response()->json([
                'message' => 'Please provide either password or OTP',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        return response()->json([
            'message' => 'Driver not found',
            'status' => 'failure',
            'statusCode' => '404',
        ], 404);
    }

    public function registerDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:drivers,email|max:255',
            'country_code' => 'required|string|max:5',
            'contact'    => 'required|string|max:15|unique:drivers,contact',
            'password'   => 'required|string|min:6',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max size
            'otp'        => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 422);
        }

        $tempOtp = TempOtp::where('country_code', $request->country_code)
            ->where('contact', $request->contact)
            ->where('otp', $request->otp)
            ->where('type', 'driver')
            ->first();

        if (!$tempOtp) {
            return response()->json([
                'message' => 'Invalid OTP',
                'status' => 'success',
                'statusCode' => '200',
            ], 200);
        }

        // Create the driver record first without the image path
        $driver = Driver::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'country_code' => $request->country_code,
            'contact'    => $request->contact,
            'flag'       => $request->flag,
            'password'   => $request->password,
            'image'      => 'NULL',
            'status'     => 'Disapprove',
            'wallet_balance' => 0,
            'verified'   => 'no',
            'otp'        => $request->otp,
        ]);

        // Store image if present
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = 'profileImage.' . $extension;

            // Define the path in the storage public folder
            $imageDirectory = 'Driver/' . $driver->id;

            // Store the image in the storage/app/public/User/{user_id} directory
            $imagePath = $image->storeAs($imageDirectory, $imageName, 'public'); // Use the public disk

            $driver->update(['image' => $imagePath]);
        }

        return response()->json([
            'message' => 'Driver registered successfully in step 1',
            'status' => 'success',
            'statusCode' => '200',
            'is_login'   => false,
            'driver' => $driver,
        ], 201);
    }

    public function completeDriverProfile(Request $request)
    {
        $driver = auth('driver')->user();

        $validator = Validator::make($request->all(), [
            'vehicle_brand'   => 'required|string|max:255',
            'vehicle_colour'  => 'required|string|max:50',
            'vehicle_date'    => 'required|string',
            'vehicle_no'      => 'required|string|max:50',
            'vehicle_size'    => 'required|string|max:50',
            'licence_image'   => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max size
            'insurance_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'ownership_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            // Optional fields from the first step or others
            'vehicle_id'      => 'nullable|string|max:255',
            'vehicle_name'    => 'nullable|string|max:255',
            'driver_lat'      => 'nullable|numeric|min:-90|max:90',
            'driver_long'     => 'nullable|numeric|min:-180|max:180',
            'device_status'   => 'nullable|string|max:200',
            'driver_device_id' => 'nullable|string|max:200',
            'iosdriver_device_id' => 'nullable|string|max:500',
            'login_status'    => 'nullable|integer|in:0,1',
            'login_device_key' => 'nullable|string|max:255',
            'access_token'    => 'nullable|string|max:255',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Prepare paths for storing images
        $licenceImagePath = null;
        $insuranceImagePath = null;
        $ownershipImagePath = null;

        // Store licence_image if present
        if ($request->hasFile('licence_image')) {
            $licenceImage = $request->file('licence_image');
            $licenceImageName = 'licenceImage.' . $licenceImage->getClientOriginalExtension();
            $licenceImageDirectory = 'Driver/' . $driver->id;
            $licenceImagePath = $licenceImage->storeAs($licenceImageDirectory, $licenceImageName, 'public');
        }

        // Store insurance_image if present
        if ($request->hasFile('insurance_image')) {
            $insuranceImage = $request->file('insurance_image');
            $insuranceImageName = 'insuranceImage.' . $insuranceImage->getClientOriginalExtension();
            $insuranceImageDirectory = 'Driver/' . $driver->id;
            $insuranceImagePath = $insuranceImage->storeAs($insuranceImageDirectory, $insuranceImageName, 'public');
        }

        // Store ownership_image if present
        if ($request->hasFile('ownership_image')) {
            $ownershipImage = $request->file('ownership_image');
            $ownershipImageName = 'ownershipImage.' . $ownershipImage->getClientOriginalExtension();
            $ownershipImageDirectory = 'Driver/' . $driver->id;
            $ownershipImagePath = $ownershipImage->storeAs($ownershipImageDirectory, $ownershipImageName, 'public');
        }

        // Update the driver profile with the additional information
        $driver->update([
            'vehicle_brand'   => $request->vehicle_brand,
            'vehicle_colour'  => $request->vehicle_colour,
            'vehicle_date'    => $request->vehicle_date,
            'vehicle_no'      => $request->vehicle_no,
            'vehicle_size'    => $request->vehicle_size,
            'licence_image'   => $licenceImagePath,
            'insurance_image' => $insuranceImagePath,
            'ownership_image' => $ownershipImagePath,
            'driver_lat'      => $request->driver_lat ?? $driver->driver_lat,
            'driver_long'     => $request->driver_long ?? $driver->driver_long,
            'device_status'   => $request->device_status ?? $driver->device_status,
            'driver_device_id' => $request->driver_device_id ?? $driver->driver_device_id,
            'iosdriver_device_id' => $request->iosdriver_device_id ?? $driver->iosdriver_device_id,
            'login_status'    => $request->login_status ?? $driver->login_status,
            'login_device_key' => $request->login_device_key ?? $driver->login_device_key,
            'access_token'    => $request->access_token ?? $driver->access_token,
            'vehicle_id'      => $request->vehicle_id ?? $driver->vehicle_id,
            'vehicle_name'    => $request->vehicle_name ?? $driver->vehicle_name,
            'is_login'        => true,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Driver profile completed successfully',
            'status' => 'success',
            'statusCode' => '200',
            'driver' => $driver,
        ], 200);
    }

    public function editDriver(Request $request)
    {
        $driver = auth('driver')->user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:drivers,email,' . $driver->id . '|max:255',
            'country_code' => 'required|string|max:5',
            'contact'    => 'required|string|max:15|unique:drivers,contact,' . $driver->id,
            'password'   => 'required|string|min:6',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'vehicle_brand'   => 'required|string|max:255',
            'vehicle_colour'  => 'required|string|max:50',
            'vehicle_date'    => 'required|string',
            'vehicle_no'      => 'required|string|max:50',
            'vehicle_size'    => 'required|string|max:50',
            'licence_image'   => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'insurance_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'ownership_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'driver_lat'      => 'nullable|numeric|min:-90|max:90',
            'driver_long'     => 'nullable|numeric|min:-180|max:180',
            'device_status'   => 'nullable|string|max:200',
            'driver_device_id' => 'nullable|string|max:200',
            'iosdriver_device_id' => 'nullable|string|max:500',
            'login_status'    => 'nullable|integer|in:0,1',
            'login_device_key' => 'nullable|string|max:255',
            'access_token'    => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'country_code',
            'contact',
            'password',
            'driver_lat',
            'driver_long',
            'device_status',
            'driver_device_id',
            'iosdriver_device_id',
            'login_status',
            'login_device_key',
            'access_token',
        ]);

        $driver->update($data);

        if ($request->hasFile('image')) {
            if ($driver->image) {
                Storage::disk('public')->delete($driver->image);
            }
            $image = $request->file('image');
            $imageName = 'profileImage.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('Driver/' . $driver->id, $imageName, 'public');
            $driver->update(['image' => $imagePath]);
        }

        if ($request->hasFile('licence_image')) {
            if ($driver->licence_image) {
                Storage::disk('public')->delete($driver->licence_image);
            }
            $licenceImage = $request->file('licence_image');
            $licenceImageName = 'licenceImage.' . $licenceImage->getClientOriginalExtension();
            $licenceImagePath = $licenceImage->storeAs('Driver/' . $driver->id, $licenceImageName, 'public');
            $driver->update(['licence_image' => $licenceImagePath]);
        }

        if ($request->hasFile('insurance_image')) {
            if ($driver->insurance_image) {
                Storage::disk('public')->delete($driver->insurance_image);
            }
            $insuranceImage = $request->file('insurance_image');
            $insuranceImageName = 'insuranceImage.' . $insuranceImage->getClientOriginalExtension();
            $insuranceImagePath = $insuranceImage->storeAs('Driver/' . $driver->id, $insuranceImageName, 'public');
            $driver->update(['insurance_image' => $insuranceImagePath]);
        }

        if ($request->hasFile('ownership_image')) {
            if ($driver->ownership_image) {
                Storage::disk('public')->delete($driver->ownership_image);
            }
            $ownershipImage = $request->file('ownership_image');
            $ownershipImageName = 'ownershipImage.' . $ownershipImage->getClientOriginalExtension();
            $ownershipImagePath = $ownershipImage->storeAs('Driver/' . $driver->id, $ownershipImageName, 'public');
            $driver->update(['ownership_image' => $ownershipImagePath]);
        }

        return response()->json([
            'message' => 'Driver profile updated successfully',
            'status' => 'success',
            'statusCode' => '200',
            'driver' => $driver,
        ], 200);
    }

    public function deleteDriver($id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json([
                'message' => 'Driver not found',
                'status' => 'error',
                'statusCode' => '404',
            ], 404);
        }

        $driver->delete();

        return response()->json([
            'message' => 'Driver deleted successfully',
            'status' => 'success',
            'statusCode' => '200',
        ], 200);
    }
    public function listDrivers()
    {
        $drivers = Driver::all();

        return response()->json([
            'message' => 'Drivers retrieved successfully',
            'status' => 'success',
            'statusCode' => '200',
            'drivers' => $drivers,
        ], 200);
    }

}
