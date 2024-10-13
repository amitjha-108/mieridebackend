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

        $createdByAdminId = null;
        $createdBySubroleUserId = null;

        if (auth('admin')->user()->token()->guard_name == 'admin') {
            $createdByAdminId = auth('admin')->user()->id;
            $createdByRoleId = auth('admin')->user()->role_id;
        }
        elseif(auth('subroleuser')->user()->token()->guard_name == 'subroleuser') {
            $createdBySubroleUserId = auth('subroleuser')->user()->id;
            $createdByRoleId = auth('subroleuser')->user()->role_id;
        }

        $role = Role::create([
            'name' => $request->name,
            'created_by_role_id' => $createdByRoleId,
            'created_by_admin_id' => $createdByAdminId,
            'created_by_subroleuser_id' => $createdBySubroleUserId,
        ]);

        return response()->json([
            'message' => 'Role created successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $role,
        ], 200);
    }


    public function listRoles()
    {
        $createdByAdminId = null;
        $createdBySubroleUserId = null;

        if (auth('admin')->user()->token()->guard_name == 'admin') {
            $createdByAdminId = auth('admin')->user()->id;
            // $roles = Role::select('id', 'name')->where('created_by_admin_id', $createdByAdminId)->with('permissions')->get();
            $roles = Role::select('id', 'name')->with('permissions')->get();
        }
        elseif (auth('subroleuser')->user()->token()->guard_name == 'subroleuser') {
            $createdBySubroleUserId = auth('subroleuser')->user()->id;
            $createdByRoleId = auth('subroleuser')->user()->role_id;
            $roles = Role::select('id', 'name')->where('created_by_subroleuser_id', $createdBySubroleUserId)->orWhere('created_by_role_id',$createdByRoleId)->with('permissions')->get();
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
