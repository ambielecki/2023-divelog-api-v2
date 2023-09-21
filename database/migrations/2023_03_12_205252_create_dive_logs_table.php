<?php

use Illuminate\Support\Facades\DB;
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
            $table->text('buddy');
            $table->dateTime('date_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('max_depth_ft')->nullable();
            $table->integer('bottom_time_min')->nullable();
            $table->integer('surface_interval_min')->nullable();
            $table->boolean('used_computer')->default(0);
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
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
