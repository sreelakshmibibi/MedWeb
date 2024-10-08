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
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreignId('app_id')->constrained('appointments');
            $table->string('diagnosis', 500);
            $table->foreignId('doctor_id')->constrained('users');
            $table->dateTime('cdate');
            $table->string('status', 5)->default('Y');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');

            $table->timestamp('updt')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('patient_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // Indexesdiagnosis
            $table->index('patient_id');
            $table->index('app_id');
            $table->index('diagnosis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnoses', function (Blueprint $table) {
            $table->dropForeign(['app_id']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
        Schema::dropIfExists('diagnoses');
    }
};
