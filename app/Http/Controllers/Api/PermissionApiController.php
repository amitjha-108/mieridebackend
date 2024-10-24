<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Admin;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PermissionApiController extends Controller
{
    public function assignPermissionToRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id'   => 'required|integer|exists:roles,id|unique:permissions,role_id,',
            'dashboard' => 'nullable|integer',
            'admins' => 'nullable|integer',
            'subadmin' => 'nullable|integer',
            'users' => 'nullable|integer',
            'drivers' => 'nullable|integer',
            'manager' => 'nullable|integer',
            'accountant' => 'nullable|integer',
            'marketing' => 'nullable|integer',
            'b_dashboard' => 'nullable|integer',
            'sharing_new_booking' => 'nullable|integer',
            'sharing_confirm' => 'nullable|integer',
            'sharing_assign' => 'nullable|integer',
            'sharing_accept' => 'nullable|integer',
            'sharing_en_route' => 'nullable|integer',
            'sharing_complete' => 'nullable|integer',
            'sharing_cancel' => 'nullable|integer',
            'private_new_booking' => 'nullable|integer',
            'private_confirm' => 'nullable|integer',
            'private_assign' => 'nullable|integer',
            'private_accept' => 'nullable|integer',
            'private_en_route' => 'nullable|integer',
            'private_complete' => 'nullable|integer',
            'private_cancel' => 'nullable|integer',
            'toairport_new_booking' => 'nullable|integer',
            'toairport_confirm' => 'nullable|integer',
            'toairport_assign' => 'nullable|integer',
            'toairport_accept' => 'nullable|integer',
            'toairport_en_route' => 'nullable|integer',
            'toairport_complete' => 'nullable|integer',
            'toairport_cancel' => 'nullable|integer',
            'fromairport_new_booking' => 'nullable|integer',
            'fromairport_confirm' => 'nullable|integer',
            'fromairport_assign' => 'nullable|integer',
            'fromairport_accept' => 'nullable|integer',
            'fromairport_en_route' => 'nullable|integer',
            'fromairport_complete' => 'nullable|integer',
            'fromairport_cancel' => 'nullable|integer',
            'drivetest_new_booking' => 'nullable|integer',
            'drivetest_confirm' => 'nullable|integer',
            'drivetest_assign' => 'nullable|integer',
            'drivetest_accept' => 'nullable|integer',
            'drivetest_en_route' => 'nullable|integer',
            'drivetest_complete' => 'nullable|integer',
            'drivetest_cancel' => 'nullable|integer',
            'intercity_new_booking' => 'nullable|integer',
            'intercity_confirm' => 'nullable|integer',
            'intercity_assign' => 'nullable|integer',
            'intercity_accept' => 'nullable|integer',
            'intercity_en_route' => 'nullable|integer',
            'intercity_complete' => 'nullable|integer',
            'intercity_cancel' => 'nullable|integer',
            'ab_new_booking' => 'nullable|integer',
            'ab_confirm' => 'nullable|integer',
            'ab_cancel' => 'nullable|integer',
            'route_new_booking' => 'nullable|integer',
            'route_confirm' => 'nullable|integer',
            'route_cancel' => 'nullable|integer',
            'out_of_area' => 'nullable|integer',
            'cs_dashboard' => 'nullable|integer',
            'chat_support' => 'nullable|integer',
            'fm_dashboard' => 'nullable|integer',
            'deposits' => 'nullable|integer',
            'd_withdraw' => 'nullable|integer',
            'switch' => 'nullable|integer',
            'ac_dashboard' => 'nullable|integer',
            'ads_user' => 'nullable|integer',
            'ads_on_route' => 'nullable|integer',
            'ads_end_receipt' => 'nullable|integer',
            'ads_driver' => 'nullable|integer',
            'notify' => 'nullable|integer',
            'deals_user' => 'nullable|integer',
            'deals_driver' => 'nullable|integer',
            'website' => 'nullable|integer',
            'pc_dashboard' => 'nullable|integer',
            'categeries' => 'nullable|integer',
            'cities' => 'nullable|integer',
            'add_sharing' => 'nullable|integer',
            'add_private' => 'nullable|integer',
            'add_to_airport' => 'nullable|integer',
            'add_from_airport' => 'nullable|integer',
            'add_drive' => 'nullable|integer',
            'add_intercity' => 'nullable|integer',
            'rates' => 'nullable|integer',
            'surge' => 'nullable|integer',
            'commission' => 'nullable|integer',
            'gt_charges' => 'nullable|integer',
            'interac_id' => 'nullable|integer',
            'payout_info' => 'nullable|integer',
            'pc_cancel' => 'nullable|integer',
            'r_dashboard' => 'nullable|integer',
            'report' => 'nullable|integer',
            's_dashboard' => 'nullable|integer',
            'faq_user' => 'nullable|integer',
            'faq_driver' => 'nullable|integer',
            'tc_user' => 'nullable|integer',
            'tc_driver' => 'nullable|integer',
            'pp_user' => 'nullable|integer',
            'pp_driver' => 'nullable|integer',
            'support' => 'nullable|integer',
            'date' => 'nullable|integer',
            'time' => 'nullable|integer',
            'is_read' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fail',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $permission = Permission::create([
            'role_id' => $request->role_id,
            'dashboard' => $request->dashboard ?? 0,
            'admins' => $request->admins ?? 0,
            'subadmin' => $request->subadmin ?? 0,
            'users' => $request->users ?? 0,
            'drivers' => $request->drivers ?? 0,
            'manager' => $request->manager ?? 0,
            'accountant' => $request->accountant ?? 0,
            'marketing' => $request->marketing ?? 0,
            'b_dashboard' => $request->b_dashboard ?? 0,
            'sharing_new_booking' => $request->sharing_new_booking ?? 0,
            'sharing_confirm' => $request->sharing_confirm ?? 0,
            'sharing_assign' => $request->sharing_assign ?? 0,
            'sharing_accept' => $request->sharing_accept ?? 0,
            'sharing_en_route' => $request->sharing_en_route ?? 0,
            'sharing_complete' => $request->sharing_complete ?? 0,
            'sharing_cancel' => $request->sharing_cancel ?? 0,
            'private_new_booking' => $request->private_new_booking ?? 0,
            'private_confirm' => $request->private_confirm ?? 0,
            'private_assign' => $request->private_assign ?? 0,
            'private_accept' => $request->private_accept ?? 0,
            'private_en_route' => $request->private_en_route ?? 0,
            'private_complete' => $request->private_complete ?? 0,
            'private_cancel' => $request->private_cancel ?? 0,
            'toairport_new_booking' => $request->toairport_new_booking ?? 0,
            'toairport_confirm' => $request->toairport_confirm ?? 0,
            'toairport_assign' => $request->toairport_assign ?? 0,
            'toairport_accept' => $request->toairport_accept ?? 0,
            'toairport_en_route' => $request->toairport_en_route ?? 0,
            'toairport_complete' => $request->toairport_complete ?? 0,
            'toairport_cancel' => $request->toairport_cancel ?? 0,
            'fromairport_new_booking' => $request->fromairport_new_booking ?? 0,
            'fromairport_confirm' => $request->fromairport_confirm ?? 0,
            'fromairport_assign' => $request->fromairport_assign ?? 0,
            'fromairport_accept' => $request->fromairport_accept ?? 0,
            'fromairport_en_route' => $request->fromairport_en_route ?? 0,
            'fromairport_complete' => $request->fromairport_complete ?? 0,
            'fromairport_cancel' => $request->fromairport_cancel ?? 0,
            'drivetest_new_booking' => $request->drivetest_new_booking ?? 0,
            'drivetest_confirm' => $request->drivetest_confirm ?? 0,
            'drivetest_assign' => $request->drivetest_assign ?? 0,
            'drivetest_accept' => $request->drivetest_accept ?? 0,
            'drivetest_en_route' => $request->drivetest_en_route ?? 0,
            'drivetest_complete' => $request->drivetest_complete ?? 0,
            'drivetest_cancel' => $request->drivetest_cancel ?? 0,
            'intercity_new_booking' => $request->intercity_new_booking ?? 0,
            'intercity_confirm' => $request->intercity_confirm ?? 0,
            'intercity_assign' => $request->intercity_assign ?? 0,
            'intercity_accept' => $request->intercity_accept ?? 0,
            'intercity_en_route' => $request->intercity_en_route ?? 0,
            'intercity_complete' => $request->intercity_complete ?? 0,
            'intercity_cancel' => $request->intercity_cancel ?? 0,
            'ab_new_booking' => $request->ab_new_booking ?? 0,
            'ab_confirm' => $request->ab_confirm ?? 0,
            'ab_cancel' => $request->ab_cancel ?? 0,
            'route_new_booking' => $request->route_new_booking ?? 0,
            'route_confirm' => $request->route_confirm ?? 0,
            'route_cancel' => $request->route_cancel ?? 0,
            'out_of_area' => $request->out_of_area ?? 0,
            'cs_dashboard' => $request->cs_dashboard ?? 0,
            'chat_support' => $request->chat_support ?? 0,
            'fm_dashboard' => $request->fm_dashboard ?? 0,
            'deposits' => $request->deposits ?? 0,
            'd_withdraw' => $request->d_withdraw ?? 0,
            'switch' => $request->switch ?? 0,
            'ac_dashboard' => $request->ac_dashboard ?? 0,
            'ads_user' => $request->ads_user ?? 0,
            'ads_on_route' => $request->ads_on_route ?? 0,
            'ads_end_receipt' => $request->ads_end_receipt ?? 0,
            'ads_driver' => $request->ads_driver ?? 0,
            'notify' => $request->notify ?? 0,
            'deals_user' => $request->deals_user ?? 0,
            'deals_driver' => $request->deals_driver ?? 0,
            'website' => $request->website ?? 0,
            'pc_dashboard' => $request->pc_dashboard ?? 0,
            'categeries' => $request->categeries ?? 0,
            'cities' => $request->cities ?? 0,
            'add_sharing' => $request->add_sharing ?? 0,
            'add_private' => $request->add_private ?? 0,
            'add_to_airport' => $request->add_to_airport ?? 0,
            'add_from_airport' => $request->add_from_airport ?? 0,
            'add_drive' => $request->add_drive ?? 0,
            'add_intercity' => $request->add_intercity ?? 0,
            'rates' => $request->rates ?? 0,
            'surge' => $request->surge ?? 0,
            'commission' => $request->commission ?? 0,
            'gt_charges' => $request->gt_charges ?? 0,
            'interac_id' => $request->interac_id ?? 0,
            'payout_info' => $request->payout_info ?? 0,
            'pc_cancel' => $request->pc_cancel ?? 0,
            'r_dashboard' => $request->r_dashboard ?? 0,
            'report' => $request->report ?? 0,
            's_dashboard' => $request->s_dashboard ?? 0,
            'faq_user' => $request->faq_user ?? 0,
            'faq_driver' => $request->faq_driver ?? 0,
            'tc_user' => $request->tc_user ?? 0,
            'tc_driver' => $request->tc_driver ?? 0,
            'pp_user' => $request->pp_user ?? 0,
            'pp_driver' => $request->pp_driver ?? 0,
            'support' => $request->support ?? 0,
            'date' => $request->date ?? 0,
            'time' => $request->time ?? 0,
            'is_read' => 0,
        ]);

        return response()->json([
            'message' => 'Permission created successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $permission
        ], 201);
    }

    public function updatePermissionForRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id'   => 'required|integer|exists:permissions,role_id',
            'dashboard' => 'nullable|integer',
            'admins' => 'nullable|integer',
            'subadmin' => 'nullable|integer',
            'users' => 'nullable|integer',
            'drivers' => 'nullable|integer',
            'manager' => 'nullable|integer',
            'accountant' => 'nullable|integer',
            'marketing' => 'nullable|integer',
            'b_dashboard' => 'nullable|integer',
            'sharing_new_booking' => 'nullable|integer',
            'sharing_confirm' => 'nullable|integer',
            'sharing_assign' => 'nullable|integer',
            'sharing_accept' => 'nullable|integer',
            'sharing_en_route' => 'nullable|integer',
            'sharing_complete' => 'nullable|integer',
            'sharing_cancel' => 'nullable|integer',
            'private_new_booking' => 'nullable|integer',
            'private_confirm' => 'nullable|integer',
            'private_assign' => 'nullable|integer',
            'private_accept' => 'nullable|integer',
            'private_en_route' => 'nullable|integer',
            'private_complete' => 'nullable|integer',
            'private_cancel' => 'nullable|integer',
            'toairport_new_booking' => 'nullable|integer',
            'toairport_confirm' => 'nullable|integer',
            'toairport_assign' => 'nullable|integer',
            'toairport_accept' => 'nullable|integer',
            'toairport_en_route' => 'nullable|integer',
            'toairport_complete' => 'nullable|integer',
            'toairport_cancel' => 'nullable|integer',
            'fromairport_new_booking' => 'nullable|integer',
            'fromairport_confirm' => 'nullable|integer',
            'fromairport_assign' => 'nullable|integer',
            'fromairport_accept' => 'nullable|integer',
            'fromairport_en_route' => 'nullable|integer',
            'fromairport_complete' => 'nullable|integer',
            'fromairport_cancel' => 'nullable|integer',
            'drivetest_new_booking' => 'nullable|integer',
            'drivetest_confirm' => 'nullable|integer',
            'drivetest_assign' => 'nullable|integer',
            'drivetest_accept' => 'nullable|integer',
            'drivetest_en_route' => 'nullable|integer',
            'drivetest_complete' => 'nullable|integer',
            'drivetest_cancel' => 'nullable|integer',
            'intercity_new_booking' => 'nullable|integer',
            'intercity_confirm' => 'nullable|integer',
            'intercity_assign' => 'nullable|integer',
            'intercity_accept' => 'nullable|integer',
            'intercity_en_route' => 'nullable|integer',
            'intercity_complete' => 'nullable|integer',
            'intercity_cancel' => 'nullable|integer',
            'ab_new_booking' => 'nullable|integer',
            'ab_confirm' => 'nullable|integer',
            'ab_cancel' => 'nullable|integer',
            'route_new_booking' => 'nullable|integer',
            'route_confirm' => 'nullable|integer',
            'route_cancel' => 'nullable|integer',
            'out_of_area' => 'nullable|integer',
            'cs_dashboard' => 'nullable|integer',
            'chat_support' => 'nullable|integer',
            'fm_dashboard' => 'nullable|integer',
            'deposits' => 'nullable|integer',
            'd_withdraw' => 'nullable|integer',
            'switch' => 'nullable|integer',
            'ac_dashboard' => 'nullable|integer',
            'ads_user' => 'nullable|integer',
            'ads_on_route' => 'nullable|integer',
            'ads_end_receipt' => 'nullable|integer',
            'ads_driver' => 'nullable|integer',
            'notify' => 'nullable|integer',
            'deals_user' => 'nullable|integer',
            'deals_driver' => 'nullable|integer',
            'website' => 'nullable|integer',
            'pc_dashboard' => 'nullable|integer',
            'categeries' => 'nullable|integer',
            'cities' => 'nullable|integer',
            'add_sharing' => 'nullable|integer',
            'add_private' => 'nullable|integer',
            'add_to_airport' => 'nullable|integer',
            'add_from_airport' => 'nullable|integer',
            'add_drive' => 'nullable|integer',
            'add_intercity' => 'nullable|integer',
            'rates' => 'nullable|integer',
            'surge' => 'nullable|integer',
            'commission' => 'nullable|integer',
            'gt_charges' => 'nullable|integer',
            'interac_id' => 'nullable|integer',
            'payout_info' => 'nullable|integer',
            'pc_cancel' => 'nullable|integer',
            'r_dashboard' => 'nullable|integer',
            'report' => 'nullable|integer',
            's_dashboard' => 'nullable|integer',
            'faq_user' => 'nullable|integer',
            'faq_driver' => 'nullable|integer',
            'tc_user' => 'nullable|integer',
            'tc_driver' => 'nullable|integer',
            'pp_user' => 'nullable|integer',
            'pp_driver' => 'nullable|integer',
            'support' => 'nullable|integer',
            'date' => 'nullable|integer',
            'time' => 'nullable|integer',
            'is_read' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed',
                'status' => 'failure',
                'statusCode' => '400',
                'data' => $validator->errors(),
            ], 400);
        }

        $permission = Permission::where('role_id', $request->role_id)->first();

        if (!$permission) {
            return response()->json([
                'message' => 'Permission not found for the specified role',
                'status' => 'failure',
                'statusCode' => '404',
            ], 404);
        }

        $permission->update($request->only([
            'dashboard', 'admins', 'subadmin', 'users', 'drivers', 'manager', 'accountant', 'marketing',
            'b_dashboard', 'sharing_new_booking', 'sharing_confirm', 'sharing_assign', 'sharing_accept',
            'sharing_en_route', 'sharing_complete', 'sharing_cancel', 'private_new_booking', 'private_confirm',
            'private_assign', 'private_accept', 'private_en_route', 'private_complete', 'private_cancel',
            'toairport_new_booking', 'toairport_confirm', 'toairport_assign', 'toairport_accept', 'toairport_en_route',
            'toairport_complete', 'toairport_cancel', 'fromairport_new_booking', 'fromairport_confirm', 'fromairport_assign',
            'fromairport_accept', 'fromairport_en_route', 'fromairport_complete', 'fromairport_cancel', 'drivetest_new_booking',
            'drivetest_confirm', 'drivetest_assign', 'drivetest_accept', 'drivetest_en_route', 'drivetest_complete', 'drivetest_cancel',
            'intercity_new_booking', 'intercity_confirm', 'intercity_assign', 'intercity_accept', 'intercity_en_route',
            'intercity_complete', 'intercity_cancel', 'ab_new_booking', 'ab_confirm', 'ab_cancel', 'route_new_booking',
            'route_confirm', 'route_cancel', 'out_of_area', 'cs_dashboard', 'chat_support', 'fm_dashboard', 'deposits',
            'd_withdraw', 'switch', 'ac_dashboard', 'ads_user', 'ads_on_route', 'ads_end_receipt', 'ads_driver', 'notify',
            'deals_user', 'deals_driver', 'website', 'pc_dashboard', 'categeries', 'cities', 'add_sharing', 'add_private',
            'add_to_airport', 'add_from_airport', 'add_drive', 'add_intercity', 'rates', 'surge', 'commission', 'gt_charges',
            'interac_id', 'payout_info', 'pc_cancel', 'r_dashboard', 'report', 's_dashboard', 'faq_user', 'faq_driver',
            'tc_user', 'tc_driver', 'pp_user', 'pp_driver', 'support', 'date', 'time', 'is_read'
        ]));

        return response()->json([
            'message' => 'Permission updated successfully',
            'status' => 'success',
            'statusCode' => '200',
            'data' => $permission,
        ], 200);
    }

    public function permissionNamesList()
    {
        $columns = Schema::getColumnListing('permissions');

        $exclude = ['id', 'role_id','admin_id','date','time', 'created_at', 'updated_at'];
        $filteredColumns = array_diff($columns, $exclude);

        return response()->json([
            'message' => 'Permission field names fetched successfully',
            'status' => 'success',
            'statusCode' => 200,
            'data' => array_values($filteredColumns),
        ],200);
    }

    public function listRolesWithPermissions()
    {
        $user = auth('admin')->user();

        if (!$user || !$user->is_superadmin) {
            return response()->json([
                'message' => 'Unauthorized access',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

        $exclude = ['id', 'role_id', 'admin_id', 'date', 'time', 'created_at', 'updated_at'];

        $roles = Role::select('id', 'name')
            ->with(['permissions' => function ($query) {
                $query->select('permissions.*');
            }])->where('name', '!=', 'Superadmin')->get();

        // Now loop over each role and apply makeHidden to each permission
        $roles->each(function ($role) use ($exclude) {
            $role->permissions->each(function ($permission) use ($exclude) {
                $permission->makeHidden($exclude);
            });
        });


        $roleNames = Role::select('id', 'name')->where('name', '!=', 'Superadmin')->get();
        $permissionColumns = Schema::getColumnListing('permissions');
        $exclude = ['id', 'role_id','admin_id','date','time', 'created_at', 'updated_at'];
        $filteredpermissionColumns = array_diff($permissionColumns, $exclude);


        return response()->json([
            'message' => 'Roles and permissions retrieved successfully',
            'status' => 'success',
            'statusCode' => 200,
            'roleNames' => $roleNames,
            'permissionNames' => array_values($filteredpermissionColumns),
            'data' => $roles,
        ], 200);
    }


}
