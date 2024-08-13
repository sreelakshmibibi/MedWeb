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
        Schema::create('prescription_detail_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('patient_prescription_billings');
            $table->foreignId('medicine_id')->constrained('medicines');
            $table->integer('quantity')->nullable();
            $table->decimal('cost', 10, 3)->nullable();
            $table->decimal('discount', 10, 3)->nullable();
            $table->decimal('amount', 10, 3)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->char('status')->default('Y')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_detail_billings');
    }
};
