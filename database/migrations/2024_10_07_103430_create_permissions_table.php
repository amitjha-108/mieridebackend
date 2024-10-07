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
            $table->string('dashboard', 25)->nullable();
            $table->string('admins', 25)->nullable();
            $table->string('subadmin', 25)->nullable();
            $table->string('users', 25)->nullable();
            $table->string('drivers', 25)->nullable();
            $table->string('manager', 25)->nullable();
            $table->string('accountant', 25)->nullable();
            $table->string('marketing', 25)->nullable();
            $table->string('b_dashboard', 25)->nullable();
            $table->string('sharing_new_booking', 25)->nullable();
            $table->string('sharing_confirm', 25)->nullable();
            $table->string('sharing_assign', 25)->nullable();
            $table->string('sharing_accept', 25)->nullable();
            $table->string('sharing_en_route', 25)->nullable();
            $table->string('sharing_complete', 25)->nullable();
            $table->string('sharing_cancel', 25)->nullable();
            $table->string('private_new_booking', 25)->nullable();
            $table->string('private_confirm', 25)->nullable();
            $table->string('private_assign', 25)->nullable();
            $table->string('private_accept', 25)->nullable();
            $table->string('private_en_route', 25)->nullable();
            $table->string('private_complete', 25)->nullable();
            $table->string('private_cancel', 25)->nullable();
            $table->string('toairport_new_booking', 25)->nullable();
            $table->string('toairport_confirm', 25)->nullable();
            $table->string('toairport_assign', 25)->nullable();
            $table->string('toairport_accept', 25)->nullable();
            $table->string('toairport_en_route', 25)->nullable();
            $table->string('toairport_complete', 25)->nullable();
            $table->string('toairport_cancel', 25)->nullable();
            $table->string('fromairport_new_booking', 25)->nullable();
            $table->string('fromairport_confirm', 25)->nullable();
            $table->string('fromairport_assign', 25)->nullable();
            $table->string('fromairport_accept', 25)->nullable();
            $table->string('fromairport_en_route', 25)->nullable();
            $table->string('fromairport_complete', 25)->nullable();
            $table->string('fromairport_cancel', 25)->nullable();
            $table->string('drivetest_new_booking', 25)->nullable();
            $table->string('drivetest_confirm', 25)->nullable();
            $table->string('drivetest_assign', 25)->nullable();
            $table->string('drivetest_accept', 25)->nullable();
            $table->string('drivetest_en_route', 25)->nullable();
            $table->string('drivetest_complete', 25)->nullable();
            $table->string('drivetest_cancel', 25)->nullable();
            $table->string('intercity_new_booking', 25)->nullable();
            $table->string('intercity_confirm', 25)->nullable();
            $table->string('intercity_assign', 25)->nullable();
            $table->string('intercity_accept', 25)->nullable();
            $table->string('intercity_en_route', 25)->nullable();
            $table->string('intercity_complete', 25)->nullable();
            $table->string('intercity_cancel', 25)->nullable();
            $table->string('ab_new_booking', 25)->nullable();
            $table->string('ab_confirm', 25)->nullable();
            $table->string('ab_cancel', 25)->nullable();
            $table->string('route_new_booking', 25)->nullable();
            $table->string('route_confirm', 25)->nullable();
            $table->string('route_cancel', 25)->nullable();
            $table->string('out_of_area', 25)->nullable();
            $table->text('cs_dashboard', 25)->nullable();
            $table->text('chat_support', 50)->nullable();
            $table->text('fm_dashboard', 25)->nullable();
            $table->text('deposits', 25)->nullable();
            $table->text('d_withdraw', 25)->nullable();
            $table->text('switch', 25)->nullable();
            $table->text('ac_dashboard', 25)->nullable();
            $table->text('ads_user', 25)->nullable();
            $table->text('ads_on_route', 25)->nullable();
            $table->text('ads_end_receipt', 25)->nullable();
            $table->text('ads_driver', 25)->nullable();
            $table->text('notify', 25)->nullable();
            $table->text('deals_user', 25)->nullable();
            $table->text('deals_driver', 25)->nullable();
            $table->text('website', 25)->nullable();
            $table->text('pc_dashboard', 25)->nullable();
            $table->text('categeries', 25)->nullable();
            $table->text('cities', 25)->nullable();
            $table->text('add_sharing', 25)->nullable();
            $table->text('add_private', 25)->nullable();
            $table->text('add_to_airport', 25)->nullable();
            $table->text('add_from_airport', 55)->nullable();
            $table->text('add_drive', 25)->nullable();
            $table->text('add_intercity', 25)->nullable();
            $table->text('rates', 25)->nullable();
            $table->text('surge', 25)->nullable();
            $table->text('commission', 25)->nullable();
            $table->text('gt_charges', 25)->nullable();
            $table->text('interac_id', 25)->nullable();
            $table->text('payout_info', 25)->nullable();
            $table->text('pc_cancel', 25)->nullable();
            $table->text('r_dashboard', 25)->nullable();
            $table->text('report', 25)->nullable();
            $table->text('s_dashboard', 25)->nullable();
            $table->text('faq_user', 25)->nullable();
            $table->text('faq_driver', 25)->nullable();
            $table->text('tc_user', 25)->nullable();
            $table->text('tc_driver', 25)->nullable();
            $table->text('pp_user', 25)->nullable();
            $table->text('pp_driver', 25)->nullable();
            $table->text('support', 25)->nullable();
            $table->text('date', 20)->nullable();
            $table->text('time', 20)->nullable();
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
