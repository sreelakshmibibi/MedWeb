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
        Schema::create('income_reports', function (Blueprint $table) {
            $table->id();
            $table->string('bill_type');
            $table->string('bill_no');
            $table->dateTime('bill_date');
            $table->decimal('net_paid', 10, 3);
            $table->decimal('cash', 10, 3)->nullable();
            $table->decimal('gpay', 10, 3)->nullable(); 
            $table->decimal('card', 10, 3)->nullable(); 
            $table->foreignId('card_pay_id')->nullable()->constrained('card_pays');
            $table->decimal('machine_tax', 10, 3)->nullable(); 
            $table->decimal('balance_given', 10, 3)->nullable();
            $table->decimal('net_income', 10, 3);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_reports');
    }
};
