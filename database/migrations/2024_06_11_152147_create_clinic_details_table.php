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
        Schema::create('clinic_details', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_name');
            $table->string('clinic_logo')->nullable();
            $table->string('clinic_address')->nullable();
            $table->foreignId('clinic_type_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_details');
    }
};
