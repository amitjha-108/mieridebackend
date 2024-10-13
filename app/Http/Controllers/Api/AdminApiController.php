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
            'is_superadmin' => 'required|boolean',
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
            'wallet_money' => 0,
            'status' => 'active',
            'device_status' => 'active',
            'parent_id' => null,
            'create_child' => 1,
            'is_superadmin' => $request->is_superadmin,
            ]);

        // Grant all permissions to superadmin
        if ($administrator->is_superadmin) {
            Permission::updateOrCreate(
                ['admin_id' => $administrator->id],
                [
                    'dashboard' => 1,
                    'admins' => 1,
                    'subadmin' => 1,
                    'users' => 1,
                    'drivers' => 1,
                    'manager' => 1,
                    'accountant' => 1,
                    'marketing' => 1,
                    'b_dashboard' => 1,
                    'sharing_new_booking' => 1,
                    'sharing_confirm' => 1,
                    'sharing_assign' => 1,
                    'sharing_accept' => 1,
                    'sharing_en_route' => 1,
                    'sharing_complete' => 1,
                    'sharing_cancel' => 1,
                    'private_new_booking' => 1,
                    'private_confirm' => 1,
                    'private_assign' => 1,
                    'private_accept' => 1,
                    'private_en_route' => 1,
                    'private_complete' => 1,
                    'private_cancel' => 1,
                    'toairport_new_booking' => 1,
                    'toairport_confirm' => 1,
                    'toairport_assign' => 1,
                    'toairport_accept' => 1,
                    'toairport_en_route' => 1,
                    'toairport_complete' => 1,
                    'toairport_cancel' => 1,
                    'fromairport_new_booking' => 1,
                    'fromairport_confirm' => 1,
                    'fromairport_assign' => 1,
                    'fromairport_accept' => 1,
                    'fromairport_en_route' => 1,
                    'fromairport_complete' => 1,
                    'fromairport_cancel' => 1,
                    'drivetest_new_booking' => 1,
                    'drivetest_confirm' => 1,
                    'drivetest_assign' => 1,
                    'drivetest_accept' => 1,
                    'drivetest_en_route' => 1,
                    'drivetest_complete' => 1,
                    'drivetest_cancel' => 1,
                    'intercity_new_booking' => 1,
                    'intercity_confirm' => 1,
                    'intercity_assign' => 1,
                    'intercity_accept' => 1,
                    'intercity_en_route' => 1,
                    'intercity_complete' => 1,
                    'intercity_cancel' => 1,
                    'ab_new_booking' => 1,
                    'ab_confirm' => 1,
                    'ab_cancel' => 1,
                    'route_new_booking' => 1,
                    'route_confirm' => 1,
                    'route_cancel' => 1,
                    'out_of_area' => 1,
                    'cs_dashboard' => 1,
                    'chat_support' => 1,
                    'fm_dashboard' => 1,
                    'deposits' => 1,
                    'd_withdraw' => 1,
                    'switch' => 1,
                    'ac_dashboard' => 1,
                    'ads_user' => 1,
                    'ads_on_route' => 1,
                    'ads_end_receipt' => 1,
                    'ads_driver' => 1,
                    'notify' => 1,
                    'deals_user' => 1,
                    'deals_driver' => 1,
                    'website' => 1,
                    'pc_dashboard' => 1,
                    'categeries' => 1,
                    'cities' => 1,
                    'add_sharing' => 1,
                    'add_private' => 1,
                    'add_to_airport' => 1,
                    'add_from_airport' => 1,
                    'add_drive' => 1,
                    'add_intercity' => 1,
                    'rates' => 1,
                    'surge' => 1,
                    'commission' => 1,
                    'gt_charges' => 1,
                    'interac_id' => 1,
                    'payout_info' => 1,
                    'pc_cancel' => 1,
                    'r_dashboard' => 1,
                    'report' => 1,
                    's_dashboard' => 1,
                    'faq_user' => 1,
                    'faq_driver' => 1,
                    'tc_user' => 1,
                    'tc_driver' => 1,
                    'pp_user' => 1,
                    'pp_driver' => 1,
                    'support' => 1,
                    'date' => 1,
                    'time' => 1,
                    'is_read' => 1,
                ]
            );
        }
        else {
            Permission::updateOrCreate(
                ['admin_id' => $administrator->id],
                [
                    'dashboard' => 0,
                    'admins' => 0,
                    'subadmin' => 0,
                    'users' => 0,
                    'drivers' => 0,
                    'manager' => 0,
                    'accountant' => 0,
                    'marketing' => 0,
                    'b_dashboard' => 0,
                    'sharing_new_booking' => 0,
                    'sharing_confirm' => 0,
                    'sharing_assign' => 0,
                    'sharing_accept' => 0,
                    'sharing_en_route' => 0,
                    'sharing_complete' => 0,
                    'sharing_cancel' => 0,
                    'private_new_booking' => 0,
                    'private_confirm' => 0,
                    'private_assign' => 0,
                    'private_accept' => 0,
                    'private_en_route' => 0,
                    'private_complete' => 0,
                    'private_cancel' => 0,
                    'toairport_new_booking' => 0,
                    'toairport_confirm' => 0,
                    'toairport_assign' => 0,
                    'toairport_accept' => 0,
                    'toairport_en_route' => 0,
                    'toairport_complete' => 0,
                    'toairport_cancel' => 0,
                    'fromairport_new_booking' => 0,
                    'fromairport_confirm' => 0,
                    'fromairport_assign' => 0,
                    'fromairport_accept' => 0,
                    'fromairport_en_route' => 0,
                    'fromairport_complete' => 0,
                    'fromairport_cancel' => 0,
                    'drivetest_new_booking' => 0,
                    'drivetest_confirm' => 0,
                    'drivetest_assign' => 0,
                    'drivetest_accept' => 0,
                    'drivetest_en_route' => 0,
                    'drivetest_complete' => 0,
                    'drivetest_cancel' => 0,
                    'intercity_new_booking' => 0,
                    'intercity_confirm' => 0,
                    'intercity_assign' => 0,
                    'intercity_accept' => 0,
                    'intercity_en_route' => 0,
                    'intercity_complete' => 0,
                    'intercity_cancel' => 0,
                    'ab_new_booking' => 0,
                    'ab_confirm' => 0,
                    'ab_cancel' => 0,
                    'route_new_booking' => 0,
                    'route_confirm' => 0,
                    'route_cancel' => 0,
                    'out_of_area' => 0,
                    'cs_dashboard' => 0,
                    'chat_support' => 0,
                    'fm_dashboard' => 0,
                    'deposits' => 0,
                    'd_withdraw' => 0,
                    'switch' => 0,
                    'ac_dashboard' => 0,
                    'ads_user' => 0,
                    'ads_on_route' => 0,
                    'ads_end_receipt' => 0,
                    'ads_driver' => 0,
                    'notify' => 0,
                    'deals_user' => 0,
                    'deals_driver' => 0,
                    'website' => 0,
                    'pc_dashboard' => 0,
                    'categeries' => 0,
                    'cities' => 0,
                    'add_sharing' => 0,
                    'add_private' => 0,
                    'add_to_airport' => 0,
                    'add_from_airport' => 0,
                    'add_drive' => 0,
                    'add_intercity' => 0,
                    'rates' => 0,
                    'surge' => 0,
                    'commission' => 0,
                    'gt_charges' => 0,
                    'interac_id' => 0,
                    'payout_info' => 0,
                    'pc_cancel' => 0,
                    'r_dashboard' => 0,
                    'report' => 0,
                    's_dashboard' => 0,
                    'faq_user' => 0,
                    'faq_driver' => 0,
                    'tc_user' => 0,
                    'tc_driver' => 0,
                    'pp_user' => 0,
                    'pp_driver' => 0,
                    'support' => 0,
                    'date' => 0,
                    'time' => 0,
                    'is_read' => 0,
                ]
            );
        }

        $tokenResult = $administrator->createToken('auth_token');
        $tokenResult->token->guard_name = 'admin';
        $tokenResult->token->save();

        return response()->json([
            'message' => 'Superadmin created successfully',
            'status' => 'success',
            'statusCode' => '200',
            'token' => $tokenResult->accessToken,
            'tokenGuard' => $tokenResult->token->guard_name,
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

        $permissions = [];
        $permissions = Permission::where('admin_id', $administrator->id)->orWhere('role_id',$administrator->role_id)
                        ->get()->makeHidden(['admin_id','created_at','updated_at']);

        $grantedPermissions = $permissions->map(function ($permission) {
            return collect($permission)->filter(function ($value, $key) {
                return $value != 0 && !in_array($key, ['id', 'role_id']);
            });
        });

        $tokenResult = $administrator->createToken('auth_token');
        $tokenResult->token->guard_name = 'admin';
        $tokenResult->token->save();

        return response()->json([
            'message' => 'Login successfully',
            'status' => 'success',
            'statusCode' => '200',
            'userType' => $administrator->role_id,
            'createChild' => $administrator->create_child,
            'token' => $tokenResult->accessToken,
            'tokenGuard' => $tokenResult->token->guard_name,
            'data' => $administrator,
            'permissions' => $grantedPermissions,
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

         // Add guard_name to token
        $tokenResult = $subroleuser->createToken('auth_token');
        $tokenResult->token->guard_name = 'subroleuser';
        $tokenResult->token->save();

        return response()->json([
            'message' => 'Subrole User Login successfully',
            'status' => 'success',
            'statusCode' => '200',
            'userType' => $subroleuser->role_id,
            'createChild' => $subroleuser->create_child,
            'token' => $tokenResult->accessToken,
            'tokenGuard' => $tokenResult->token->guard_name,
            'data' => $subroleuser,
            'permissions' => $permissions,
        ], 200);
    }

    public function createAdmin(Request $request)
    {
        $user = auth('admin')->user();

        if (!$user || !$user->is_superadmin) {
            return response()->json([
                'message' => 'Unauthorized access',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|max:150|unique:admins,username',
            'email' => 'required|string|email|max:150|unique:admins,email',
            'country_code' => 'required|string|max:10',
            'contact_no' => 'required|string|max:15|unique:admins,contact_no',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        // Create the admin
        $admin = Admin::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'country_code' => $request->country_code,
            'contact_no' => $request->contact_no,
            'password' => $request->password,
            'image' => $request->image,
            'wallet_money' => 0,
            'status' => 'active',
            'device_status' => 'active',
            'parent_id' => $user->id,
            'create_child' => 1,
            'is_superadmin' => 0,
            'role_id' => $request->role_id,
        ]);

        $permissions = [];
        $permissions = Permission::where('role_id', $admin->role_id)->get()->makeHidden(['admin_id','created_at','updated_at']);
        $grantedPermissions = $permissions->map(function ($permission) {
            return collect($permission)->filter(function ($value, $key) {
                return $value != 0 && !in_array($key, ['id', 'role_id']);
            });
        });

        $tokenResult = $admin->createToken('auth_token');
        $tokenResult->token->guard_name = 'admin';
        $tokenResult->token->save();

        return response()->json([
            'message' => 'Admin created successfully',
            'status' => 'success',
            'statusCode' => '200',
            'token' => $tokenResult->accessToken,
            'admin' => $admin,
            'permissions' => $grantedPermissions,
        ], 200);
    }

    public function listAdmins()
    {
        $admins = Admin::whereNotNull('parent_id')->whereNotNull('role_id')->orderBy('created_at', 'desc')->get();

        // Loop through each admin and fetch their permissions
        $adminsWithPermissions = $admins->map(function ($admin) {
            // Get permissions for the admin's role
            $permissions = Permission::where('role_id', $admin->role_id)->get()->makeHidden(['admin_id','created_at','updated_at']);

            // Filter out permission values that are 0
            $grantedPermissions = $permissions->map(function ($permission) {
                return collect($permission)->filter(function ($value, $key) {
                    return $value != 0 && !in_array($key, ['id', 'role_id']);
                });
            });

            // Return admin details along with their granted permissions
            return [
                'admin' => $admin,
                'permissions' => $grantedPermissions
            ];
        });

        return response()->json([
            'message' => 'Admins retrieved successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $adminsWithPermissions
        ], 200);
    }


}
