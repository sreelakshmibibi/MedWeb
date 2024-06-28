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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id')->unique();
            $table->unsignedBigInteger('patient_id');
            $table->date('app_date');
            $table->time('app_time');
            $table->foreignId('doctor_id')->constrained('users');
            $table->foreignId('app_branch')->constrained('clinic_branches');
            $table->foreignId('app_type')->constrained('appointment_types');
            $table->decimal('height_cm', 5, 2); // For height in centimeters
            $table->decimal('weight_kg', 5, 2); // For weight in kilograms
            $table->string('referred_doctor', 100)->nullable();
            $table->string('appointment_note', 300)->nullable();
            $table->string('nursing_note', 500)->nullable();
            $table->string('doctor_note', 500)->nullable();
            $table->string('doctor_check', 5)->default('N');
            $table->foreignId('app_status')->constrained('appointment_statuses');
            $table->date('next_app_date')->nullable();
            $table->time('next_app_time')->nullable();
            $table->foreignId('next_app_branch')->constrained('clinic_branches');
            $table->string('status', 20);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('patient_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->index('app_id');
            $table->index('doctor_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['app_branch']);
            $table->dropForeign(['app_type']);
            $table->dropForeign(['app_status']);
            $table->dropForeign(['next_app_branch']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
        Schema::dropIfExists('appointments');
    }
};
