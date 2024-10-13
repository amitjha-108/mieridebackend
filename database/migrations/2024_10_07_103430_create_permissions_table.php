<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');

            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            $table->integer('dashboard')->nullable();
            $table->integer('admins')->nullable();
            $table->integer('subadmin')->nullable();
            $table->integer('users')->nullable();
            $table->integer('drivers')->nullable();
            $table->integer('manager')->nullable();
            $table->integer('accountant')->nullable();
            $table->integer('marketing')->nullable();
            $table->integer('b_dashboard')->nullable();
            $table->integer('sharing_new_booking')->nullable();
            $table->integer('sharing_confirm')->nullable();
            $table->integer('sharing_assign')->nullable();
            $table->integer('sharing_accept')->nullable();
            $table->integer('sharing_en_route')->nullable();
            $table->integer('sharing_complete')->nullable();
            $table->integer('sharing_cancel')->nullable();
            $table->integer('private_new_booking')->nullable();
            $table->integer('private_confirm')->nullable();
            $table->integer('private_assign')->nullable();
            $table->integer('private_accept')->nullable();
            $table->integer('private_en_route')->nullable();
            $table->integer('private_complete')->nullable();
            $table->integer('private_cancel')->nullable();
            $table->integer('toairport_new_booking')->nullable();
            $table->integer('toairport_confirm')->nullable();
            $table->integer('toairport_assign')->nullable();
            $table->integer('toairport_accept')->nullable();
            $table->integer('toairport_en_route')->nullable();
            $table->integer('toairport_complete')->nullable();
            $table->integer('toairport_cancel')->nullable();
            $table->integer('fromairport_new_booking')->nullable();
            $table->integer('fromairport_confirm')->nullable();
            $table->integer('fromairport_assign')->nullable();
            $table->integer('fromairport_accept')->nullable();
            $table->integer('fromairport_en_route')->nullable();
            $table->integer('fromairport_complete')->nullable();
            $table->integer('fromairport_cancel')->nullable();
            $table->integer('drivetest_new_booking')->nullable();
            $table->integer('drivetest_confirm')->nullable();
            $table->integer('drivetest_assign')->nullable();
            $table->integer('drivetest_accept')->nullable();
            $table->integer('drivetest_en_route')->nullable();
            $table->integer('drivetest_complete')->nullable();
            $table->integer('drivetest_cancel')->nullable();
            $table->integer('intercity_new_booking')->nullable();
            $table->integer('intercity_confirm')->nullable();
            $table->integer('intercity_assign')->nullable();
            $table->integer('intercity_accept')->nullable();
            $table->integer('intercity_en_route')->nullable();
            $table->integer('intercity_complete')->nullable();
            $table->integer('intercity_cancel')->nullable();
            $table->integer('ab_new_booking')->nullable();
            $table->integer('ab_confirm')->nullable();
            $table->integer('ab_cancel')->nullable();
            $table->integer('route_new_booking')->nullable();
            $table->integer('route_confirm')->nullable();
            $table->integer('route_cancel')->nullable();
            $table->integer('out_of_area')->nullable();
            $table->integer('cs_dashboard')->nullable();
            $table->integer('chat_support')->nullable();
            $table->integer('fm_dashboard')->nullable();
            $table->integer('deposits')->nullable();
            $table->integer('d_withdraw')->nullable();
            $table->integer('switch')->nullable();
            $table->integer('ac_dashboard')->nullable();
            $table->integer('ads_user')->nullable();
            $table->integer('ads_on_route')->nullable();
            $table->integer('ads_end_receipt')->nullable();
            $table->integer('ads_driver')->nullable();
            $table->integer('notify')->nullable();
            $table->integer('deals_user')->nullable();
            $table->integer('deals_driver')->nullable();
            $table->integer('website')->nullable();
            $table->integer('pc_dashboard')->nullable();
            $table->integer('categeries')->nullable();
            $table->integer('cities')->nullable();
            $table->integer('add_sharing')->nullable();
            $table->integer('add_private')->nullable();
            $table->integer('add_to_airport')->nullable();
            $table->integer('add_from_airport')->nullable();
            $table->integer('add_drive')->nullable();
            $table->integer('add_intercity')->nullable();
            $table->integer('rates')->nullable();
            $table->integer('surge')->nullable();
            $table->integer('commission')->nullable();
            $table->integer('gt_charges')->nullable();
            $table->integer('interac_id')->nullable();
            $table->integer('payout_info')->nullable();
            $table->integer('pc_cancel')->nullable();
            $table->integer('r_dashboard')->nullable();
            $table->integer('report')->nullable();
            $table->integer('s_dashboard')->nullable();
            $table->integer('faq_user')->nullable();
            $table->integer('faq_driver')->nullable();
            $table->integer('tc_user')->nullable();
            $table->integer('tc_driver')->nullable();
            $table->integer('pp_user')->nullable();
            $table->integer('pp_driver')->nullable();
            $table->integer('support')->nullable();
            $table->integer('date')->nullable();
            $table->integer('time')->nullable();
            $table->integer('is_read')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
