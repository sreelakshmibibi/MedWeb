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
        Schema::create('teeths', function (Blueprint $table) {
            $table->id();
            $table->string('teeth_name')->unique();
            $table->string('position');
            $table->string('direction');
            $table->string('teeth_image');
            $table->string('is_pediatric')->nullable();
            $table->integer('position_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teeths');
    }
};
