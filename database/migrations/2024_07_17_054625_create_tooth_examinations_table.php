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
        Schema::create('tooth_examinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreignId('app_id')->constrained('appointments');
            $table->foreignId('tooth_id')->constrained('teeths');
            $table->foreignId('tooth_score_id')->constrained('tooth_scores');
            $table->string('chief_complaint');
            $table->string('hpi');
            $table->string('dental_examination');
            $table->string('diagnosis');
            $table->string('treatment');
            $table->string('x-ray')->nullable();
            $table->foreignId('lingual_condn')->constrained('surface_conditions')->nullable();
            $table->foreignId('labial_condn')->constrained('surface_conditions')->nullable();
            $table->foreignId('occulusal_condn')->constrained('surface_conditions')->nullable();
            $table->foreignId('distal_condn')->constrained('surface_conditions')->nullable();
            $table->foreignId('mesial_condn')->constrained('surface_conditions')->nullable();
            $table->foreignId('palatal_condn')->constrained('surface_conditions')->nullable();
            $table->foreignId('buccal_condn')->constrained('surface_conditions')->nullable();
            $table->foreignId('treatment_status')->constrained('treatment_statuses');
            
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->string('status', 5)->default('Y');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tooth_examinations');
    }
};
