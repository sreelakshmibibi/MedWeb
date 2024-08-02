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
        Schema::create('patient_treatment_billings', function (Blueprint $table) {
            $table->id();
            $table->string('bill_id')->unique();
            $table->foreignId('appointment_id')->constrained('appointments');
            $table->unsignedBigInteger('app_id');
            $table->unsignedBigInteger('patient_id');
            $table->integer('treatment_total_amount')->nullable();
            $table->integer('prescription_total_amount')->nullable();
            $table->integer('total_amount')->nullable();
            $table->foreignId('combo_offer_id')->nullable()->constrained('combo_offers');
            $table->integer('previous_outstanding')->nullable();
            $table->integer('doctor_discount')->nullable();
            $table->integer('amount_to_be_paid')->nullable();
            $table->interger('amount_paid')->nullable();
            $table->integer('balance_due')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('patient_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('app_id')
                ->references('app_id')
                ->on('appointments')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_treatment_billings', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['app_id']);
            $table->dropForeign(['combo_offer_id']);
            $table->dropForeign(['appointment_id']);
        });

        Schema::dropIfExists('patient_treatment_billings');
    }
};
