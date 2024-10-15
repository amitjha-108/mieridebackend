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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('country_code');
            $table->string('contact')->unique();
            $table->string('flag', 50);
            $table->string('image', 500);
            $table->string('password');
            $table->decimal('driver_lat', 20, 6)->nullable();
            $table->decimal('driver_long', 20, 6)->nullable();
            $table->string('vehicle_id', 200)->nullable();
            $table->string('vehicle_name', 200)->nullable();
            $table->string('vehicle_brand', 200)->nullable();
            $table->string('vehicle_colour', 200)->nullable();
            $table->string('vehicle_date')->nullable();
            $table->string('vehicle_size')->nullable();
            $table->string('vehicle_no', 200)->nullable();
            $table->enum('status', ['Disapprove', 'Approve']);
            $table->string('licence_image')->nullable();
            $table->date('licence_expiry')->nullable();
            $table->boolean('licence_status')->default(false);
            $table->string('insurance_image')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->boolean('insurance_status')->default(false);
            $table->string('ownership_image')->nullable();
            $table->boolean('ownership_status')->default(false);
            $table->integer('wallet_balance')->nullable()->default(0);
            $table->string('verified', 50)->default('');
            $table->string('verification_code', 50)->default('');
            $table->string('device_status', 200)->nullable();
            $table->string('driver_device_id', 200)->nullable();
            $table->string('iosdriver_device_id', 500)->nullable();
            $table->integer('login_status')->default(0);
            $table->string('login_device_key')->default('');
            $table->string('access_token')->default('');
            $table->integer('otp')->nullable();
            $table->boolean('is_login')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
