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
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained('users');
            $table->string('phone', 20); 
            $table->foreignId('department_id')->constrained('departments');
            $table->string('specialization'); 
            $table->integer('years_of_experience')->nullable(); 
            $table->string('license_number', 50)->unique(); 
            $table->string('subspecialty')->nullable(); 
            $table->text('address')->nullable(); 
            $table->foreignId('area')->constrained('cities'); 
            $table->foreignId('state')->constrained('states'); 
            $table->foreignId('nationality')->constrained('countries'); 
            $table->integer('pin')->nullable(); 
            $table->date('date_of_birth')->nullable(); 
            $table->string('gender', 10)->nullable(); 
            $table->string('photo', 255)->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['area']);
            $table->dropForeign(['state']);
            $table->dropForeign(['nationality']);
        });
        Schema::dropIfExists('doctor_profiles');
    }
};
