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
            $table->string('clinic_insurance_available')->default('N')->nullable();
            $table->integer('patient_registration_fees')->default(0);
            $table->integer('consultation_fees')->default(0);
            $table->integer('consultation_fees_frequency')->default(0);
            $table->foreignId('clinic_type_id')->constrained();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
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
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
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
