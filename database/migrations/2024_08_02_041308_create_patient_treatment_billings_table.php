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
            $table->unsignedBigInteger('appointment_id')->constrained('appointments');
            $table->unsignedBigInteger('patient_id');
            $table->decimal('treatment_total_amount', 10, 3)->nullable();
            $table->decimal('combo_offer_deduction', 10, 3)->nullable();
            $table->decimal('previous_outstanding', 10, 3)->nullable();
            $table->decimal('doctor_discount', 10, 3)->nullable();
            $table->decimal('insurance_paid', 10, 3)->nullable();
            $table->decimal('amount_to_be_paid', 10, 3)->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->decimal('amount_paid', 10, 3)->nullable();
            $table->decimal('balance_due' , 10, 3)->nullable();
            $table->char('status')->default('Y');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('patient_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    
        Schema::dropIfExists('patient_treatment_billings');
    }
};