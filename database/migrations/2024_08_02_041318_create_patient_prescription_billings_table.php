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
        Schema::create('patient_prescription_billings', function (Blueprint $table) {
            $table->id();
            $table->string('bill_id')->unique();
            $table->unsignedBigInteger('appointment_id')->constrained('appointments');
            $table->unsignedBigInteger('patient_id');
            $table->decimal('prescription_total_amount', 10, 3)->nullable();
            $table->float('tax_percentile')->nullable();
            $table->decimal('tax', 10, 3)->nullable();
            $table->decimal('discount', 10, 3)->nullable();
            $table->decimal('amount_to_be_paid', 10, 3)->nullable();
            $table->decimal('gpay', 10, 3)->nullable();
            $table->decimal('cash', 10, 3)->nullable();
            $table->decimal('card', 10, 3)->nullable();
            $table->foreignId('card_pay_id')->nullable()->constrained('card_pays');
            $table->decimal('bank_tax', 10, 3)->nullable();
            $table->decimal('amount_paid', 10, 3)->nullable();
            $table->decimal('balance_given', 10, 3)->nullable();
            $table->dateTime('bill_paid_date')->nullable();
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

        Schema::dropIfExists('patient_prescription_billings');
    }
};
