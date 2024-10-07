<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RoleApiController extends Controller
{
    public function storeRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|max:255|unique:roles,role_name',
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

        $role = Role::create([
            'role_name' => $request->role_name,
        ]);

        return response()->json([
            'message' => 'Role created successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $role,
            ], 201);
    }

    public function listRoles()
    {
        $roles = Role::with('permissions')->get();
        return response()->json([
            'message' => 'Role list retrieved successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $roles,
            ], 200);
    }
}
