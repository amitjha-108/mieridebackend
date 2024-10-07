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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 215)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('username', 200)->nullable();
            $table->string('email', 215)->nullable();
            $table->string('country_code', 11)->nullable();
            $table->string('contact_no', 15)->nullable();
            $table->string('password', 200)->nullable();
            $table->string('image', 215)->nullable();
            $table->string('wallet_money', 255)->nullable();
            $table->string('user_type', 255)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('device_status', 215)->nullable();
            $table->string('device_id', 500)->nullable();
            $table->string('iosdevice_id', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
