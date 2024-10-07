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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('country_code', 255);
            $table->string('contact', 255)->unique();
            $table->string('email')->unique()->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('address', 200)->nullable();
            $table->boolean('is_login')->default(false);
            $table->enum('status', ['Approve', 'Disapprove'])->nullable();
            $table->string('image', 255)->nullable();
            $table->string('flag', 50)->nullable();
            $table->string('device_id', 255)->nullable();
            $table->string('iosdevice_id', 500)->nullable();
            $table->string('device_status', 200)->nullable();
            $table->string('lat', 255)->nullable();
            $table->string('long', 255)->nullable();
            $table->string('date', 100)->nullable();
            $table->integer('user_wallet')->nullable()->default(0);
            $table->integer('total_coins')->nullable();
            $table->string('social_id', 500)->nullable();
            $table->string('social_type', 50)->nullable();
            $table->string('invite_code', 50)->nullable();
            $table->string('password', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('otp')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
