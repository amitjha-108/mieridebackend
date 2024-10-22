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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('ride_categories')->onDelete('cascade');

            $table->string('source')->nullbale();
            $table->string('destination')->nullbale();

            $table->decimal('four_seater_price', 8, 2)->nullable();
            $table->decimal('six_seater_price', 8, 2)->nullable();

            $table->decimal('sharing_price', 8, 2)->nullable();

            $table->string('test_location')->nullable();
            $table->decimal('drive_price', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
