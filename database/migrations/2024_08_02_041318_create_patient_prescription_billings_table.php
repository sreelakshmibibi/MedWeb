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
            $table->foreignId('billing_id')->constrained('patient_treatment_billings');
            $table->foreignId('medicine_id')->constrained('medicines');
            $table->integer('cost');
            $table->integer('quantity');
            $table->integer('discount');
            $table->integer('amount');
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
        Schema::table('patient_prescription_billings', function (Blueprint $table) {
            $table->dropForeign(['billing_id']);
            $table->dropForeign(['medicine_id']);
        });
        Schema::dropIfExists('patient_prescription_billings');
    }
};