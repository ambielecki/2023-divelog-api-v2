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
        Schema::create('dive_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('dive_number');
            $table->text('location');
            $table->text('dive_site');
            $table->date('date');
            $table->text('description');
            $table->text('notes');
            $table->json('dive_details');
            $table->json('equipment_details');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(['user_id', 'dive_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dive_logs');
    }
};
