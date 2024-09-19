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
        Schema::create('order_placeds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreignId('tooth_examination_id')->constrained('tooth_examinations');
            $table->foreignId('treatment_plan_id')->constrained('treatment_plans');
            $table->foreignId('technician_id')->constrained('technicians');
            $table->dateTime('order_placed_on');
            $table->dateTime('delivery_expected_on');
            $table->dateTime('delivered_on')->nullable();
            $table->integer('order_status'); /* PLACED = 1;  DELIVERED = 2; CANCELLED = 3; PENDING = 4; REPEAT = 5;*/
            $table->decimal('lab_cost', 10, 3);
            $table->string('billable')->default('Y');
            $table->foreignId('lab_bill_id')->nullable()->constrained('lab_bills');
            $table->string('order_cancel_reason')->nullable();
            $table->integer('parent_order_id')->nullable();
            $table->string('order_repeat_reason')->nullable();
            $table->dateTime('cancelled_on')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->foreign('patient_id')->references('patient_id')->on('patient_profiles')->onDelete('cascade');
            $table->foreignId('canceled_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_placeds');
    }
};
