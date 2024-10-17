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
use App\Models\Subrole;

class RoleApiController extends Controller
{
    public function storeRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
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
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Role created successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $role,
        ], 200);
    }

    public function storeSubRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255|unique:roles,name',
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
            'role_id' => $request->role_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Subrole created successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $role,
        ], 200);
    }

    public function listRoles()
    {
        if (auth('admin')->check() && auth('admin')->user()->token()->guard_name == 'admin') {
            $roleId = auth('admin')->user()->role_id;
            if($roleId){
                $roles = Role::select('id', 'name')->with('permissions')->where('name', '!=', 'Superadmin')->where('role_id',$roleId)->get();
            }
            else{
                $roles = Role::select('id', 'name')->with('permissions')->where('name', '!=', 'Superadmin')->get();
            }

        }
        elseif (auth('subroleuser')->check() && auth('subroleuser')->user()->token()->guard_name == 'subroleuser') {
            $roleId = auth('subroleuser')->user()->role_id;
            $roles = Role::select('id', 'name')->where('role_id', $roleId)->where('name', '!=', 'Superadmin')->with('permissions')->get();
        }

        // Return the list of roles
        return response()->json([
            'message' => 'Role list retrieved successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $roles,
        ], 200);
    }

}
