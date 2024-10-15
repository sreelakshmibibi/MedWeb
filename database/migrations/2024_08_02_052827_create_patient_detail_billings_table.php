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
        Schema::create('patient_detail_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_id')->constrained('patient_treatment_billings');
            $table->foreignId('treatment_id')->nullable()->constrained('treatment_types');
            $table->foreignId('plan_id')->nullable()->constrained('treatment_plans');
            $table->string('consultation_registration_xray')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('cost', 10, 3);
            $table->decimal('discount', 10, 3);
            $table->decimal('amount', 10, 3);
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
        Schema::table('patient_detail_billings', function (Blueprint $table) {
            $table->dropForeign(['billing_id']);
            $table->dropForeign(['treatment_id']);
        });
        Schema::dropIfExists('patient_detail_billings');
    }
};
