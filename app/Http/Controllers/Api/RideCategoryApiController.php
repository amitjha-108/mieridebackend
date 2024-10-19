<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RideCategory;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RideCategoryApiController extends Controller
{
    public function storeRideCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ride_categories,name',
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

        $user = auth('admin')->user();

        if (!$user || !$user->is_superadmin) {
            return response()->json([
                'message' => 'Unauthorized access',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        $rideCat = RideCategory::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Ride category created successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $rideCat,
        ], 200);
    }

    public function editRideCategory(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ride_categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $user = auth('admin')->user();

        if (!$user || !$user->is_superadmin) {
            return response()->json([
                'message' => 'Unauthorized access',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        $rideCategory = RideCategory::find($id);

        if (!$rideCategory) {
            return response()->json([
                'message' => 'Ride category not found',
                'status' => 'failure',
                'statusCode' => '404',
            ], 404);
        }

        $rideCategory->name = $request->name;
        $rideCategory->save();

        return response()->json([
            'message' => 'Ride category updated successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $rideCategory,
        ], 200);
    }


    public function listRideCategories()
    {

        $rideCategories = RideCategory::all()->makeHidden(['created_at', 'updated_at']);

        return response()->json([
            'message' => 'Ride categories fetched successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $rideCategories,
        ], 200);
    }

    public function deleteRideCategory($id)
    {
        $user = auth('admin')->user();

        if (!$user || !$user->is_superadmin) {
            return response()->json([
                'message' => 'Unauthorized access',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        $rideCategory = RideCategory::find($id);

        if (!$rideCategory) {
            return response()->json([
                'message' => 'Ride category not found',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        $rideCategory->delete();

        return response()->json([
            'message' => 'Ride category deleted successfully',
            'status' => 'success',
            'statusCode' => '200',
        ], 200);
    }

    public function updateRideCategoryStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $user = auth('admin')->user();

        if (!$user || !$user->is_superadmin) {
            return response()->json([
                'message' => 'Unauthorized access',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        $rideCategory = RideCategory::find($id);

        if (!$rideCategory) {
            return response()->json([
                'message' => 'Ride category not found',
                'status' => 'failure',
                'statusCode' => '404',
            ], 404);
        }

        $rideCategory->status = $request->status;
        $rideCategory->save();

        return response()->json([
            'message' => 'Ride category status updated successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $rideCategory,
        ], 200);
    }



}
