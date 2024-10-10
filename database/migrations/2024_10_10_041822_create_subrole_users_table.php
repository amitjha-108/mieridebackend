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
        Schema::create('subrole_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('admins')->onDelete('cascade');

            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            $table->unsignedBigInteger('subrole_id')->nullable();
            $table->foreign('subrole_id')->references('id')->on('subroles')->onDelete('cascade');

            $table->string('first_name', 215)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('username', 200)->nullable();
            $table->string('email', 215)->nullable();
            $table->string('country_code', 11)->nullable();
            $table->string('contact_no', 15)->nullable();
            $table->string('password', 200)->nullable();
            $table->string('image', 215)->nullable();
            $table->integer('wallet_money')->nullable()->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('device_status', 215)->nullable();
            $table->string('device_id', 500)->nullable();
            $table->string('iosdevice_id', 500)->nullable();
            $table->integer('create_child')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subrole_users');
    }
};
