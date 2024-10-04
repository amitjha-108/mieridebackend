<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Admin;
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
            'user_type' => 'administrator',
            'wallet_money' => 0,
            'status' => 'active',
            'device_status' => 'active',
            // Set all permissions to 1
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
            'date' => now(),
            'time' => now(),
            'is_read' => 0,
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
            ], 400);
        }

        $token = $administrator->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'Administrator Login successfully',
            'status' => 'success',
            'statusCode' => '200',
            'token' => $token,
            'administrator' => $administrator,
        ], 200);
    }
}
