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
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained('users');
            $table->string('staff_id');
            $table->foreignId('clinic_branch_id')->constrained('clinic_branches'); 
            $table->string('title');
            $table->string('aadhaar_no');
            $table->date('date_of_birth')->nullable(); 
            $table->string('gender', 10)->nullable(); 
            $table->string('phone', 20); 
            $table->text('address1')->nullable(); 
            $table->text('address2')->nullable(); 
            $table->foreignId('city_id')->constrained('cities'); 
            $table->foreignId('state_id')->constrained('states'); 
            $table->foreignId('country_id')->constrained('countries'); 
            $table->integer('pincode')->nullable(); 
            
            $table->text('com_address1')->nullable(); //com = communication
            $table->text('com_address2')->nullable(); 
            $table->foreignId('com_city_id')->constrained('cities')->nullable(); 
            $table->foreignId('com_state_id')->constrained('states')->nullable(); 
            $table->foreignId('com_country_id')->constrained('countries')->nullable(); 
            $table->integer('com_pincode')->nullable(); 
            
            $table->string('photo', 255)->nullable(); 
            $table->date('date_of_joining');
            $table->date('date_of_relieving')->nullable();
            
            $table->string('qualification');
            $table->foreignId('department_id')->constrained('departments');
            $table->string('specialization'); 
            $table->string('years_of_experience')->nullable(); 
            $table->string('license_number', 50)->unique(); 
            $table->string('subspecialty')->nullable(); 
            $table->string('status');
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
        Schema::table('staff_profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['country_id']);
        });
        Schema::dropIfExists('staff_profiles');
    }
};
