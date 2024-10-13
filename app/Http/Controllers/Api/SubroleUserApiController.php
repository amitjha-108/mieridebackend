<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubroleUser;
use Twilio\Rest\Client;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Subrole;

class SubroleUserApiController extends Controller
{
    public function storeSubroleUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'nullable|exists:roles,id',
            'first_name' => 'required|string|max:215',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|max:150|unique:subrole_users,username',
            'email' => 'required|string|email|max:150|unique:subrole_users,email',
            'country_code' => 'required|string|max:10',
            'contact_no' => 'required|string|max:15|unique:subrole_users,contact_no',
            'password' => 'required|string|min:6|max:200',
            'image' => 'nullable|string|max:215',
            'wallet_money' => 'nullable|integer',
            'status' => 'in:active,inactive',
            'device_status' => 'nullable|string|max:215',
            'device_id' => 'nullable|string|max:500',
            'iosdevice_id' => 'nullable|string|max:500',
            'create_child' => 'nullable|boolean',
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

        // Determine if the logged-in user is an admin or a subrole user
        $loggedUser = null;
        $isAdmin = false;

        if (auth('admin')->user()->token()->guard_name == 'admin') {
            $loggedUser = auth('admin')->user();
            $isAdmin = true;
        }
        elseif (auth('subroleuser')->user()->token()->guard_name == 'subroleuser') {
            $loggedUser = auth('subroleuser')->user();
        }

        if (!$loggedUser) {
            return response()->json([
                'message' => 'Unauthorized',
                'status' => 'failure',
                'statusCode' => '401',
            ], 401);
        }

        // Create a new subrole user
        $subroleUser = new SubroleUser();
        if ($isAdmin) {
            $subroleUser->admin_id = $loggedUser->id;
        }
        else {
            $subroleUser->parent_id = $loggedUser->id;
        }
        $subroleUser->role_id = $request->role_id;
        $subroleUser->first_name = $request->first_name;
        $subroleUser->last_name = $request->last_name;
        $subroleUser->username = $request->username;
        $subroleUser->email = $request->email;
        $subroleUser->country_code = $request->country_code;
        $subroleUser->contact_no = $request->contact_no;
        $subroleUser->password = $request->password;
        $subroleUser->image = $request->image;
        $subroleUser->wallet_money = $request->wallet_money ?? 0;
        $subroleUser->status = $request->status ?? 'active';
        $subroleUser->device_status = $request->device_status;
        $subroleUser->device_id = $request->device_id;
        $subroleUser->iosdevice_id = $request->iosdevice_id;
        $subroleUser->create_child = $request->create_child ?? 0;
        $subroleUser->save();

        // Return a success response
        return response()->json([
            'message' => 'Subrole user created successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $subroleUser,
            ], 201);
    }
}
