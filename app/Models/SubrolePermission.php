<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubrolePermission extends Model
{
    use HasFactory;
    protected $fillable = [
        'subrole_user_id',
        'role_id',
        'admin_id',
        'parent_id',
        'dashboard',
        'admins',
        'subadmin',
        'users',
        'drivers',
        'manager',
        'accountant',
        'marketing',
        'b_dashboard',
        'sharing_new_booking',
        'sharing_confirm',
        'sharing_assign',
        'sharing_accept',
        'sharing_en_route',
        'sharing_complete',
        'sharing_cancel',
        'private_new_booking',
        'private_confirm',
        'private_assign',
        'private_accept',
        'private_en_route',
        'private_complete',
        'private_cancel',
        'toairport_new_booking',
        'toairport_confirm',
        'toairport_assign',
        'toairport_accept',
        'toairport_en_route',
        'toairport_complete',
        'toairport_cancel',
        'fromairport_new_booking',
        'fromairport_confirm',
        'fromairport_assign',
        'fromairport_accept',
        'fromairport_en_route',
        'fromairport_complete',
        'fromairport_cancel',
        'drivetest_new_booking',
        'drivetest_confirm',
        'drivetest_assign',
        'drivetest_accept',
        'drivetest_en_route',
        'drivetest_complete',
        'drivetest_cancel',
        'intercity_new_booking',
        'intercity_confirm',
        'intercity_assign',
        'intercity_accept',
        'intercity_en_route',
        'intercity_complete',
        'intercity_cancel',
        'ab_new_booking',
        'ab_confirm',
        'ab_cancel',
        'route_new_booking',
        'route_confirm',
        'route_cancel',
        'out_of_area',
        'cs_dashboard',
        'chat_support',
        'fm_dashboard',
        'deposits',
        'd_withdraw',
        'switch',
        'ac_dashboard',
        'ads_user',
        'ads_on_route',
        'ads_end_receipt',
        'ads_driver',
        'notify',
        'deals_user',
        'deals_driver',
        'website',
        'pc_dashboard',
        'categeries',
        'cities',
        'add_sharing',
        'add_private',
        'add_to_airport',
        'add_from_airport',
        'add_drive',
        'add_intercity',
        'rates',
        'surge',
        'commission',
        'gt_charges',
        'interac_id',
        'payout_info',
        'pc_cancel',
        'r_dashboard',
        'report',
        's_dashboard',
        'faq_user',
        'faq_driver',
        'tc_user',
        'tc_driver',
        'pp_user',
        'pp_driver',
        'support',
        'date',
        'time',
        'is_read',
    ];
}
