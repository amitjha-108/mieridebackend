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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('created_by_role_id')->nullable();
            $table->foreign('created_by_role_id')->references('id')->on('roles')->onDelete('cascade');

            $table->unsignedBigInteger('created_by_admin_id')->nullable();
            $table->unsignedBigInteger('created_by_subroleuser_id')->nullable();
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
