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
        Schema::create('lab_bills', function (Blueprint $table) {
            $table->id();
            $table->date('from_date');
            $table->date('to_date');
            $table->foreignId('branch_id')->constrained('clinic_branches')->onDelete('cascade'); // Ensures cascading deletes
            $table->foreignId('technician_id')->constrained('technicians')->onDelete('cascade'); // Ensures cascading deletes
            $table->decimal('bill_amount', 10, 3)->nullable();
            $table->decimal('previous_due', 10, 3)->nullable();
            $table->decimal('amount_to_be_paid', 10, 3);
            $table->decimal('gpay', 10, 3)->nullable();
            $table->decimal('cash', 10, 3)->nullable();
            $table->decimal('card', 10, 3)->nullable();
            $table->string('payment_through')->nullable();
            $table->decimal('amount_paid', 10, 3);
            $table->decimal('balance_due', 10, 3);
            $table->string('lab_bill_status');
            $table->string('lab_bill_delete_reason')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_bills');
    }
};
