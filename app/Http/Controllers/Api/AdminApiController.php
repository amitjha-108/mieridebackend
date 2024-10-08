<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Permission;
use App\Models\SubroleUser;
use App\Models\SubrolePermission;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminApiController extends Controller
{
    public function registerAdministrator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|max:150|unique:admins,username',
            'email' => 'required|string|email|max:150|unique:admins,email',
            'country_code' => 'required|string|max:10',
            'contact_no' => 'required|string|max:15|unique:admins,contact_no',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'administrator_create_token' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        if($request->administrator_create_token != 'keepitsecret@108'){
            return response()->json([
                'message' => 'Unautorised',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }
        $administrator = Admin::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'country_code' => $request->country_code,
            'contact_no' => $request->contact_no,
            'password' => $request->password,
            'image' => $request->image,
            'role_id' => '1',
            'wallet_money' => 0,
            'status' => 'active',
            'device_status' => 'active',
            'create_child' => 1,
            ]);

        return response()->json([
            'message' => 'Administrator created successfully',
            'status' => 'success',
            'statusCode' => '200',
            'token' => $administrator->createToken('auth_token')->accessToken,
            'administrator' => $administrator
        ], 201);
    }

    public function loginAdministrator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:150',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $administrator = Admin::where('email', $request->email)->first();

        if (!$administrator) {
            return response()->json([
                'message' => 'Email not registered',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        if ($request->password != $administrator->password) {
            return response()->json([
                'message' => 'Incorrect password',
                'status' => 'failure',
                'statusCode' => '400',
            ], 200);
        }

        $role = Role::where('id', $administrator->role_id)->first();
        $permissions = [];
        if ($role) {
            $permissions = Permission::where('role_id', $role->id)->get();
        }

        $token = $administrator->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'Administrator Login successfully',
            'status' => 'success',
            'statusCode' => '200',
            'userType' => $administrator->role_id,
            'createChild' => $administrator->create_child,
            'token' => $token,
            'data' => $administrator,
            'permissions' => $permissions,
        ], 200);
    }

    public function loginSubroleUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:150',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $subroleuser = SubroleUser::where('email', $request->email)->first();

        if (!$subroleuser) {
            return response()->json([
                'message' => 'Email not registered',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        if ($request->password != $subroleuser->password) {
            return response()->json([
                'message' => 'Incorrect password',
                'status' => 'failure',
                'statusCode' => '400',
            ], 200);
        }

        $permissions = [];
        $permissions = SubrolePermission::where('subrole_user_id', $subroleuser->id)->get();

        $token = $subroleuser->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'Subrole User Login successfully',
            'status' => 'success',
            'statusCode' => '200',
            'userType' => $subroleuser->role_id,
            'createChild' => $subroleuser->create_child,
            'token' => $token,
            'data' => $subroleuser,
            'permissions' => $permissions,
        ], 200);
    }
}
