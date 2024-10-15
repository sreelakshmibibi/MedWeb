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
        Schema::create('employee_monthly_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('month'); 
            $table->integer('year'); 
            $table->integer('working_days')->default(0); 
            $table->integer('paid_days')->default(0); 
            $table->integer('unpaid_days')->default(0); 
            $table->integer('partially_paid_days')->default(0);
            $table->foreignId('salary_id')->constrained('salaries');
            $table->decimal('basic_salary', 10, 2); 
            $table->decimal('absence_deduction', 10, 2)->default(0);
            $table->decimal('incentives', 10, 2)->default(0); // Incentives
            $table->decimal('monthly_deduction', 10, 2)->default(0);
            $table->string('deduction_reason')->nullable();
            $table->decimal('total_deduction', 10, 2)->default(0);
            $table->decimal('total_earnings', 10, 2)->default(0);
            $table->decimal('ctc', 10, 2)->default(0);
            $table->decimal('total_salary', 10, 2); // Total salary amount
            $table->decimal('previous_due')->default(0);
            $table->foreignId('advance_id')->nullable()->constrained('salary_advances');
            $table->decimal('advance_given', 10, 2)->default(0); // Advance given
            $table->decimal('amount_to_be_paid');
            $table->decimal('amount_paid')->nullable();
            $table->decimal('balance_due')->nullable()->default(0);
            $table->date('paid_on')->nullable();
            $table->decimal('cash')->nullable()->default(0);
            $table->decimal('bank')->nullable()->default(0);
            $table->char('status')->default('Y'); 
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade'); 
            $table->string('delete_reason', 255)->nullable(); // Reason for deletion 
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_monthly_salaries');
    }
};
