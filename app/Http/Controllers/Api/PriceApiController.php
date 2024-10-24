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

    public function editPersonalRidePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:prices,id',
            'category_id' => 'sometimes|required|exists:ride_categories,id',
            'source' => 'sometimes|required|string',
            'destination' => 'sometimes|required|string',
            'four_seater_price' => 'sometimes|required|numeric',
            'six_seater_price' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $price = Price::where('id',$request->id)->where('category_id',$request->category_id)->first();

        if(!$price){
            return response()->json([
                'message' => 'Invalid price id or category id',
                'status' => 'success',
                'statusCode' => '200',
            ], 200);
        }

        $price->update($request->all());

        $filteredPrice = array_filter(
            $price->makeHidden(['created_at', 'updated_at'])->toArray(),
            function ($value) {
                return !is_null($value);
            }
        );

        return response()->json([
            'message' => 'Price updated successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $filteredPrice,
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

    public function editSharingRidePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:prices,id',
            'category_id' => 'sometimes|required|exists:ride_categories,id',
            'source' => 'sometimes|required|string',
            'destination' => 'sometimes|required|string',
            'sharing_price' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $price = Price::where('id',$request->id)->where('category_id',$request->category_id)->first();

        if(!$price){
            return response()->json([
                'message' => 'Invalid price id or category id',
                'status' => 'success',
                'statusCode' => '200',
            ], 200);
        }

        $price->update($request->all());

        $filteredPrice = array_filter(
            $price->makeHidden(['created_at', 'updated_at'])->toArray(),
            function ($value) {
                return !is_null($value);
            }
        );

        return response()->json([
            'message' => 'Price updated successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $filteredPrice,
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

    public function editTestdriveRidePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:prices,id',
            'category_id' => 'sometimes|required|exists:ride_categories,id',
            'source' => 'sometimes|required|string',
            'destination' => 'sometimes|required|string',
            'test_location' => 'sometimes|required|string',
            'drive_price' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $price = Price::where('id',$request->id)->where('category_id',$request->category_id)->first();

        if(!$price){
            return response()->json([
                'message' => 'Invalid price id or category id',
                'status' => 'success',
                'statusCode' => '200',
            ], 200);
        }

          // Decode existing test_location if it's valid JSON, otherwise treat it as an array
        $existingLocations = json_decode($price->test_location, true);
        if (!is_array($existingLocations)) {
            $existingLocations = []; // Ensure it's an array if it's not valid JSON
        }

        // Add new location from request if available
        if ($request->has('test_location')) {
            $newLocations = json_decode($request->test_location, true);
            if (is_array($newLocations)) {
                $mergedLocations = array_merge($existingLocations, $newLocations); // Merge old and new locations
                $uniqueLocations = array_unique($mergedLocations); // Keep only unique locations
                $price->test_location = json_encode($uniqueLocations);
            }
        }

        $price->update([
            'category_id' => $request->category_id,
            'source' => $request->source,
            'destination' => $request->destination,
            'test_location' => $price->test_location,
            'drive_price' => $request->drive_price,
        ]);

        $filteredPrice = array_filter(
            $price->makeHidden(['created_at', 'updated_at'])->toArray(),
            function ($value) {
                return !is_null($value);
            }
        );

        return response()->json([
            'message' => 'Price updated successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $filteredPrice,
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

    public function getRidePriceList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:ride_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $prices = Price::where('category_id', $request->category_id)->get();

        if (!$prices) {
            return response()->json([
                'message' => 'Price not found',
                'status' => 'failure',
                'statusCode' => '404',
            ], 404);
        }

        // Make hidden fields and filter out null values
        $filteredPrices = $prices->makeHidden(['created_at', 'updated_at'])
        ->map(function ($price) {
            return array_filter($price->toArray(), function ($value) {
                return !is_null($value);
            });
        });

        return response()->json([
            'message' => 'Price fetched successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $filteredPrices,
        ], 200);
    }

    public function deleteRidePriceById($id)
    {
        $price = Price::find($id);

        if (!$price) {
            return response()->json([
                'message' => 'Price not found',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        $price->delete();

        return response()->json([
            'message' => 'Price deleted successfully',
            'status' => 'success',
            'statusCode' => '200',
        ], 200);
    }

}
