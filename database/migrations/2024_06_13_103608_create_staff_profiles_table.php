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
        Schema::create('staff_profifles', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained('users');
            $table->string('staff_id');
            $table->date('date_of_birth')->nullable(); 
            $table->string('gender', 10)->nullable(); 
            $table->string('phone', 20); 
            $table->string('email')->nullable(); 
            $table->text('address')->nullable(); 
            $table->foreignId('city_id')->constrained('cities'); 
            $table->foreignId('state_id')->constrained('states'); 
            $table->foreignId('country_id')->constrained('countries'); 
            $table->integer('pincode')->nullable(); 
            $table->string('photo', 255)->nullable(); 
            $table->date('date_of_joining');
            $table->date('date_of_relieving')->nullable();
            
            $table->string('qualification');
            $table->foreignId('department_id')->constrained('departments');
            $table->string('specialization'); 
            $table->integer('years_of_experience')->nullable(); 
            $table->string('license_number', 50)->unique(); 
            $table->string('subspecialty')->nullable(); 
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
