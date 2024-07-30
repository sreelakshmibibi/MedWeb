<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tooth_examinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreignId('app_id')->constrained('appointments');
            $table->string('tooth_id')->nullable();
            $table->unsignedBigInteger('row_id')->nullable();
            $table->foreignId('tooth_score_id')->nullable()->constrained('tooth_scores');
            $table->string('chief_complaint');
            $table->string('hpi');
            $table->string('dental_examination');
            $table->string('diagnosis');
            $table->foreignId('disease_id')->nullable()->constrained('diseases');
            $table->foreignId('treatment_id')->constrained('treatment_types');
            $table->foreignId('treatment_plan_id')->nullable()->constrained('treatment_plans');
            $table->string('xray')->nullable();
            $table->foreignId('lingual_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('labial_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('occulusal_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('distal_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('mesial_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('palatal_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('buccal_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('treatment_status')->constrained('treatment_statuses');
            $table->string('anatomy_image')->nullable();
            $table->string('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->string('status', 5)->default('Y');
            $table->foreign('patient_id')
            ->references('patient_id')
            ->on('patient_profiles')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->foreign('tooth_id')
            ->references('teeth_name')
            ->on('teeths')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->foreign('row_id')
            ->references('id')
            ->on('teeth_rows')
            ->onDelete('cascade')
            ->onUpdate('cascade');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('tooth_examinations');
    }
};
