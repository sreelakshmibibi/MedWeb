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
        Schema::create('clinic_basic_details', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_name');
            $table->string('clinic_logo')->nullable();
            $table->string('clinic_website')->nullable();
            $table->foreignId('clinic_type_id')->constrained();
            $table->timestamps();
            $table->softDeletes(); 
        });
        Schema::create('clinic_branches', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_email')->nullable();
            $table->string('clinic_address')->nullable();
            $table->foreignId('country_id')->constrained();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('city_id')->constrained();
            $table->string('pincode')->nullable();
            $table->string('is_main_branch')->nullable();
            $table->string('is_medicine_provided')->nullable();
            $table->string('clinic_phone')->nullable();
            $table->string('clinic_status');
            $table->foreignId('clinic_type_id')->constrained();
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_basic_details');
    
        Schema::dropIfExists('clinic_branches');
    }
};
