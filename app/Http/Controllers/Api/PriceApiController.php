<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Price;

class PriceApiController extends Controller
{
    public function storePersonalRidePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:ride_categories,id',
            'source' => 'required|string',
            'destination' => 'required|string',
            'four_seater_price' => 'required|numeric',
            'six_seater_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        // Create new Price record
        $price = Price::create($request->all());

        // Return response
        return response()->json([
            'message' => 'Price created successfully',
            'status' => 'sucess',
            'statusCode' => '200',
            'data' => $price,
        ], 200);
    }

    public function storeSharingRidePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:ride_categories,id',
            'source' => 'required|string',
            'destination' => 'required|string',
            'sharing_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        // Create new Price record
        $price = Price::create($request->all());

        // Return response
        return response()->json([
            'message' => 'Price created successfully',
            'status' => 'sucess',
            'statusCode' => '200',
            'data' => $price,
        ], 200);
    }

    public function storeTestdriveRidePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:ride_categories,id',
            'source' => 'required|string',
            'destination' => 'required|string',
            'test_location' => 'required|string',
            'drive_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        // Create new Price record
        $price = Price::create([
            'category_id' => $request->category_id,
            'source' => $request->source,
            'destination' => $request->destination,
            'test_location' => json_encode($request->test_location),
            'drive_price' => $request->drive_price,
        ]);

        // Return response
        return response()->json([
            'message' => 'Price created successfully',
            'status' => 'sucess',
            'statusCode' => '200',
            'data' => $price,
        ], 200);
    }

    public function getRidePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:ride_categories,id',
            'source' => 'required|string',
            'destination' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        // $price = Price::with('categoryDetails')->where('category_id', $request->category_id)
        $price = Price::where('category_id', $request->category_id)
                    ->where('source', $request->source)
                    ->where('destination', $request->destination)
                    ->first();

        if (!$price) {
            return response()->json([
                'message' => 'Price not found',
                'status' => 'failure',
                'statusCode' => '404',
            ], 404);
        }

        // Make hidden fields and filter out null values
        $filteredPrice = array_filter(
            $price->makeHidden(['created_at', 'updated_at'])->toArray(),
            function ($value) {
                return !is_null($value);
            }
        );

        // if($price->categoryDetails->name == "Sharing Ride"){

        // }
        return response()->json([
            'message' => 'Price fetched successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $filteredPrice,
        ], 200);
    }

}
