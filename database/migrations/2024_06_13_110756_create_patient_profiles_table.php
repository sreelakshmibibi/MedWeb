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
        Schema::create('patient_profiles', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('patient_id')->unique(); 
            $table->string('national_id', 50)->nullable(); 
            $table->string('first_name', 100); 
            $table->string('last_name', 100)->nullable(); 
            $table->string('gsm', 50)->nullable(); 
            $table->string('gender', 20)->nullable(); 
            $table->date('birth_date')->nullable(); 
            $table->integer('age')->nullable(); 
            $table->string('address')->nullable();
            $table->foreignId('area')->constrained('cities'); 
            $table->foreignId('state')->constrained('states'); 
            $table->foreignId('nationality')->constrained('countries'); 
            $table->integer('pin')->nullable(); 
            $table->string('registration_date', 50)->nullable(); 
            $table->integer('visit_count')->default(0); 
            $table->string('pstatus', 1)->default('Y'); 
            $table->string('regby', 50);
            
            
            $table->index('first_name'); 
            $table->index('last_name'); 
            $table->index('gsm'); 
            $table->index('area');
            $table->index('nationality'); 
            $table->index('visit_count'); 
            $table->index('age'); 
            $table->index('birth_date'); 
            $table->index('gender'); 
            $table->index('national_id'); 
            $table->index('regby');
            $table->timestamps(); 
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_profiles', function (Blueprint $table) {
            $table->dropForeign(['area']);
            $table->dropForeign(['state']);
            $table->dropForeign(['nationality']);
        });
        Schema::dropIfExists('patient_profiles');
    }
};
