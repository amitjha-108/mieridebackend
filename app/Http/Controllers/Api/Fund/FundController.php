<?php

namespace App\Http\Controllers\Api\Fund;

use App\Models\Fund;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\{Storage,DB};

class FundController extends Controller
{
    public function fundStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        try {
            // Start the transaction
            DB::beginTransaction();

            // Find the fund
            $fund = Fund::find($request->id);

            if (!$fund) {
                return response()->json([
                    'message' => 'Fund not found',
                    'status' => 'failure',
                    'statusCode' => '404',
                ], 404);
            }

            // Update the status
            $fund->status = $request->status;
            $fund->save();

            // Commit the transaction
            DB::commit();

            return response()->json([
                'message' => 'Status updated successfully',
                'status' => 'success',
                'statusCode' => '200',
                'data' => Fund::find($request->id),
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred',
                'status' => 'failure',
                'statusCode' => '500',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function listFunds(Request $request)
    {

        $data = Fund::latest()->when($request->input('start_date') && $request->input('end_date'), function($query) use ($request) {
                // Apply date range filter
                $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
            })
            ->when($request->input('status'), function($query) use ($request) {
                // Apply status filter
                $query->where('status', $request->input('status'));
            })
            ->paginate(20);

            // Modify the data to include the full image URL
        $data->getCollection()->transform(function($fund) {
            $baseUrl = asset('storage/fund');
            if ($fund->image) {
                $fund->image = $baseUrl.'/'.$fund->image;
            } else {
                $fund->image = null; // Set null if no image is available
            }
            return $fund;
        });

        return response()->json([
            'message' => 'Fund list retrieved successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $data,
        ], 200);
    }
}
