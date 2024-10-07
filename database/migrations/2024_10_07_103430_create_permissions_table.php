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
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->integer('dashboard', 25)->nullable();
            $table->integer('admins', 25)->nullable();
            $table->integer('subadmin', 25)->nullable();
            $table->integer('users', 25)->nullable();
            $table->integer('drivers', 25)->nullable();
            $table->integer('manager', 25)->nullable();
            $table->integer('accountant', 25)->nullable();
            $table->integer('marketing', 25)->nullable();
            $table->integer('b_dashboard', 25)->nullable();
            $table->integer('sharing_new_booking', 25)->nullable();
            $table->integer('sharing_confirm', 25)->nullable();
            $table->integer('sharing_assign', 25)->nullable();
            $table->integer('sharing_accept', 25)->nullable();
            $table->integer('sharing_en_route', 25)->nullable();
            $table->integer('sharing_complete', 25)->nullable();
            $table->integer('sharing_cancel', 25)->nullable();
            $table->integer('private_new_booking', 25)->nullable();
            $table->integer('private_confirm', 25)->nullable();
            $table->integer('private_assign', 25)->nullable();
            $table->integer('private_accept', 25)->nullable();
            $table->integer('private_en_route', 25)->nullable();
            $table->integer('private_complete', 25)->nullable();
            $table->integer('private_cancel', 25)->nullable();
            $table->integer('toairport_new_booking', 25)->nullable();
            $table->integer('toairport_confirm', 25)->nullable();
            $table->integer('toairport_assign', 25)->nullable();
            $table->integer('toairport_accept', 25)->nullable();
            $table->integer('toairport_en_route', 25)->nullable();
            $table->integer('toairport_complete', 25)->nullable();
            $table->integer('toairport_cancel', 25)->nullable();
            $table->integer('fromairport_new_booking', 25)->nullable();
            $table->integer('fromairport_confirm', 25)->nullable();
            $table->integer('fromairport_assign', 25)->nullable();
            $table->integer('fromairport_accept', 25)->nullable();
            $table->integer('fromairport_en_route', 25)->nullable();
            $table->integer('fromairport_complete', 25)->nullable();
            $table->integer('fromairport_cancel', 25)->nullable();
            $table->integer('drivetest_new_booking', 25)->nullable();
            $table->integer('drivetest_confirm', 25)->nullable();
            $table->integer('drivetest_assign', 25)->nullable();
            $table->integer('drivetest_accept', 25)->nullable();
            $table->integer('drivetest_en_route', 25)->nullable();
            $table->integer('drivetest_complete', 25)->nullable();
            $table->integer('drivetest_cancel', 25)->nullable();
            $table->integer('intercity_new_booking', 25)->nullable();
            $table->integer('intercity_confirm', 25)->nullable();
            $table->integer('intercity_assign', 25)->nullable();
            $table->integer('intercity_accept', 25)->nullable();
            $table->integer('intercity_en_route', 25)->nullable();
            $table->integer('intercity_complete', 25)->nullable();
            $table->integer('intercity_cancel', 25)->nullable();
            $table->integer('ab_new_booking', 25)->nullable();
            $table->integer('ab_confirm', 25)->nullable();
            $table->integer('ab_cancel', 25)->nullable();
            $table->integer('route_new_booking', 25)->nullable();
            $table->integer('route_confirm', 25)->nullable();
            $table->integer('route_cancel', 25)->nullable();
            $table->integer('out_of_area', 25)->nullable();
            $table->integer('cs_dashboard', 25)->nullable();
            $table->integer('chat_support', 50)->nullable();
            $table->integer('fm_dashboard', 25)->nullable();
            $table->integer('deposits', 25)->nullable();
            $table->integer('d_withdraw', 25)->nullable();
            $table->integer('switch', 25)->nullable();
            $table->integer('ac_dashboard', 25)->nullable();
            $table->integer('ads_user', 25)->nullable();
            $table->integer('ads_on_route', 25)->nullable();
            $table->integer('ads_end_receipt', 25)->nullable();
            $table->integer('ads_driver', 25)->nullable();
            $table->integer('notify', 25)->nullable();
            $table->integer('deals_user', 25)->nullable();
            $table->integer('deals_driver', 25)->nullable();
            $table->integer('website', 25)->nullable();
            $table->integer('pc_dashboard', 25)->nullable();
            $table->integer('categeries', 25)->nullable();
            $table->integer('cities', 25)->nullable();
            $table->integer('add_sharing', 25)->nullable();
            $table->integer('add_private', 25)->nullable();
            $table->integer('add_to_airport', 25)->nullable();
            $table->integer('add_from_airport', 55)->nullable();
            $table->integer('add_drive', 25)->nullable();
            $table->integer('add_intercity', 25)->nullable();
            $table->integer('rates', 25)->nullable();
            $table->integer('surge', 25)->nullable();
            $table->integer('commission', 25)->nullable();
            $table->integer('gt_charges', 25)->nullable();
            $table->integer('interac_id', 25)->nullable();
            $table->integer('payout_info', 25)->nullable();
            $table->integer('pc_cancel', 25)->nullable();
            $table->integer('r_dashboard', 25)->nullable();
            $table->integer('report', 25)->nullable();
            $table->integer('s_dashboard', 25)->nullable();
            $table->integer('faq_user', 25)->nullable();
            $table->integer('faq_driver', 25)->nullable();
            $table->integer('tc_user', 25)->nullable();
            $table->integer('tc_driver', 25)->nullable();
            $table->integer('pp_user', 25)->nullable();
            $table->integer('pp_driver', 25)->nullable();
            $table->integer('support', 25)->nullable();
            $table->integer('date', 20)->nullable();
            $table->integer('time', 20)->nullable();
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
