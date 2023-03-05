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
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('folder');
            $table->string('file_name', 100)->unique();
            $table->text('title');
            $table->text('description');
            $table->tinyInteger('is_hero')->default(0);
            $table->text('aspect_ratio')->nullable();
            $table->tinyInteger('has_high_res')->default(0);
            $table->tinyInteger('has_sizes')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
