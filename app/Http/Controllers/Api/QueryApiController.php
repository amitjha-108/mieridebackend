<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Query;
use Illuminate\Support\Facades\Validator;

class QueryApiController extends Controller
{
    public function storeQueries(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string',
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

        $query = Query::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Query submitted successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $query,
            ], 201);
    }

    public function listQueries()
    {
        $queries = Query::all();
        return response()->json([
            'message' => 'Query list retrieved successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $queries,
            ], 200);
    }

}
