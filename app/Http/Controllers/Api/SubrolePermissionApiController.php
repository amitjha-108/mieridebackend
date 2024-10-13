<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Admin;
use App\Models\User;
use App\Models\SubroleUser;
use App\Models\Permission;
use App\Models\SubrolePermission;
use App\Models\Role;
use App\Models\Subrole;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use DB;

class SubrolePermissionApiController extends Controller
{
    public function assignPermissionToSubroleUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subrole_user_id' => 'required|integer|exists:subrole_users,id|unique:subrole_permissions,subrole_user_id',
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

        // Determine if the logged-in user is an admin or subrole user
        $loggedUser = null;
        $isAdmin = false;

        if (auth('admin')->user()->token()->guard_name == 'admin') {
            $loggedUser = auth('admin')->user();
            $isAdmin = true;
        }
        elseif (auth('subroleuser')->user()->token()->guard_name == 'subroleuser') {
            $loggedUser = auth('subroleuser')->user();
        }

        // Check if the user is logged in
        if (!$loggedUser) {
            return response()->json([
                'message' => 'Unauthorized',
                'status' => 'failure',
                'statusCode' => '401',
            ], 401);
        }

        if($loggedUser->create_child == 1){
            $permission = new SubrolePermission();
            $permission->subrole_user_id = $request->subrole_user_id;
            $permission->role_id = $request->role_id;

            // If the logged-in user is an admin, set admin_id, else set parent_id
            if ($isAdmin) {
                $permission->admin_id = $loggedUser->id;
            } else {
                $permission->parent_id = $loggedUser->id;
            }

            // Assign other permissions from the request
            $permission->dashboard = $request->dashboard ?? 0;
            $permission->admins = $request->admins ?? 0;
            $permission->subadmin = $request->subadmin ?? 0;
            $permission->users = $request->users ?? 0;
            $permission->drivers = $request->drivers ?? 0;
            $permission->manager = $request->manager ?? 0;
            $permission->accountant = $request->accountant ?? 0;
            $permission->marketing = $request->marketing ?? 0;
            $permission->b_dashboard = $request->b_dashboard ?? 0;
            $permission->sharing_new_booking = $request->sharing_new_booking ?? 0;
            $permission->sharing_confirm = $request->sharing_confirm ?? 0;
            $permission->sharing_assign = $request->sharing_assign ?? 0;
            $permission->sharing_accept = $request->sharing_accept ?? 0;
            $permission->sharing_en_route = $request->sharing_en_route ?? 0;
            $permission->sharing_complete = $request->sharing_complete ?? 0;
            $permission->sharing_cancel = $request->sharing_cancel ?? 0;
            $permission->private_new_booking = $request->private_new_booking ?? 0;
            $permission->private_confirm = $request->private_confirm ?? 0;
            $permission->private_assign = $request->private_assign ?? 0;
            $permission->private_accept = $request->private_accept ?? 0;
            $permission->private_en_route = $request->private_en_route ?? 0;
            $permission->private_complete = $request->private_complete ?? 0;
            $permission->private_cancel = $request->private_cancel ?? 0;
            $permission->toairport_new_booking = $request->toairport_new_booking ?? 0;
            $permission->toairport_confirm = $request->toairport_confirm ?? 0;
            $permission->toairport_assign = $request->toairport_assign ?? 0;
            $permission->toairport_accept = $request->toairport_accept ?? 0;
            $permission->toairport_en_route = $request->toairport_en_route ?? 0;
            $permission->toairport_complete = $request->toairport_complete ?? 0;
            $permission->toairport_cancel = $request->toairport_cancel ?? 0;
            $permission->fromairport_new_booking = $request->fromairport_new_booking ?? 0;
            $permission->fromairport_confirm = $request->fromairport_confirm ?? 0;
            $permission->fromairport_assign = $request->fromairport_assign ?? 0;
            $permission->fromairport_accept = $request->fromairport_accept ?? 0;
            $permission->fromairport_en_route = $request->fromairport_en_route ?? 0;
            $permission->fromairport_complete = $request->fromairport_complete ?? 0;
            $permission->fromairport_cancel = $request->fromairport_cancel ?? 0;
            $permission->drivetest_new_booking = $request->drivetest_new_booking ?? 0;
            $permission->drivetest_confirm = $request->drivetest_confirm ?? 0;
            $permission->drivetest_assign = $request->drivetest_assign ?? 0;
            $permission->drivetest_accept = $request->drivetest_accept ?? 0;
            $permission->drivetest_en_route = $request->drivetest_en_route ?? 0;
            $permission->drivetest_complete = $request->drivetest_complete ?? 0;
            $permission->drivetest_cancel = $request->drivetest_cancel ?? 0;
            $permission->intercity_new_booking = $request->intercity_new_booking ?? 0;
            $permission->intercity_confirm = $request->intercity_confirm ?? 0;
            $permission->intercity_assign = $request->intercity_assign ?? 0;
            $permission->intercity_accept = $request->intercity_accept ?? 0;
            $permission->intercity_en_route = $request->intercity_en_route ?? 0;
            $permission->intercity_complete = $request->intercity_complete ?? 0;
            $permission->intercity_cancel = $request->intercity_cancel ?? 0;
            $permission->ab_new_booking = $request->ab_new_booking ?? 0;
            $permission->ab_confirm = $request->ab_confirm ?? 0;
            $permission->ab_cancel = $request->ab_cancel ?? 0;
            $permission->route_new_booking = $request->route_new_booking ?? 0;
            $permission->route_confirm = $request->route_confirm ?? 0;
            $permission->route_cancel = $request->route_cancel ?? 0;
            $permission->out_of_area = $request->out_of_area ?? 0;
            $permission->cs_dashboard = $request->cs_dashboard ?? 0;
            $permission->chat_support = $request->chat_support ?? 0;
            $permission->fm_dashboard = $request->fm_dashboard ?? 0;
            $permission->deposits = $request->deposits ?? 0;
            $permission->d_withdraw = $request->d_withdraw ?? 0;
            $permission->switch = $request->switch ?? 0;
            $permission->ac_dashboard = $request->ac_dashboard ?? 0;
            $permission->ads_user = $request->ads_user ?? 0;
            $permission->ads_on_route = $request->ads_on_route ?? 0;
            $permission->ads_end_receipt = $request->ads_end_receipt ?? 0;
            $permission->ads_driver = $request->ads_driver ?? 0;
            $permission->notify = $request->notify ?? 0;
            $permission->deals_user = $request->deals_user ?? 0;
            $permission->deals_driver = $request->deals_driver ?? 0;
            $permission->website = $request->website ?? 0;
            $permission->pc_dashboard = $request->pc_dashboard ?? 0;
            $permission->categeries = $request->categeries ?? 0;
            $permission->cities = $request->cities ?? 0;
            $permission->add_sharing = $request->add_sharing ?? 0;
            $permission->add_private = $request->add_private ?? 0;
            $permission->add_to_airport = $request->add_to_airport ?? 0;
            $permission->add_from_airport = $request->add_from_airport ?? 0;
            $permission->add_drive = $request->add_drive ?? 0;
            $permission->add_intercity = $request->add_intercity ?? 0;
            $permission->rates = $request->rates ?? 0;
            $permission->surge = $request->surge ?? 0;
            $permission->commission = $request->commission ?? 0;
            $permission->gt_charges = $request->gt_charges ?? 0;
            $permission->interac_id = $request->interac_id ?? 0;
            $permission->payout_info = $request->payout_info ?? 0;
            $permission->pc_cancel = $request->pc_cancel ?? 0;
            $permission->r_dashboard = $request->r_dashboard ?? 0;
            $permission->report = $request->report ?? 0;
            $permission->s_dashboard = $request->s_dashboard ?? 0;
            $permission->faq_user = $request->faq_user ?? 0;
            $permission->faq_driver = $request->faq_driver ?? 0;
            $permission->tc_user = $request->tc_user ?? 0;
            $permission->tc_driver = $request->tc_driver ?? 0;
            $permission->pp_user = $request->pp_user ?? 0;
            $permission->pp_driver = $request->pp_driver ?? 0;
            $permission->support = $request->support ?? 0;
            $permission->date = $request->date ?? 0;
            $permission->time = $request->time ?? 0;
            $permission->is_read = 0;

            $permission->save();

            return response()->json([
                'message' => 'Permission created successfully',
                'status' => 'success',
                'statusCode' => '200',
                'data' => $permission
            ], 201);
        }
        else{
            return response()->json([
                'message' => 'Unauthorized to assign permissions',
                'status' => 'failure',
                'statusCode' => '400',
            ], 400);
        }

    }

}
