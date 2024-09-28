<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('clinic_branches')->onDelete('cascade');
            $table->enum('purchase_category', ['M', 'O'])->comment('M - Medicine, O - Other')->default('O');
            $table->string('invoice_no');
            $table->date('invoice_date');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->enum('category', ['D', 'C'])->comment('D - Debit, C - Credit');
            $table->decimal('item_subtotal');
            $table->decimal('delivery_charge')->nullable()->default(0);
            $table->decimal('gst')->nullable()->default(0);
            $table->decimal('total_currentbill');
            $table->decimal('discount')->nullable()->default(0);
            $table->decimal('previous_due')->nullable();
            $table->decimal('amount_to_be_paid');
            $table->decimal('gpay')->nullable()->default(0);
            $table->decimal('cash')->nullable()->default(0);
            $table->decimal('card')->nullable()->default(0);
            $table->decimal('amount_paid');
            $table->decimal('balance_due')->nullable()->default(0);
            $table->decimal('balance_to_give_back')->nullable()->default(0);
            $table->string('balance_given', 1);
            $table->string('consider_for_next_payment', 1);
            $table->string('billfile', 255)->nullable();
            $table->string('purchase_delete_reason', 255)->nullable();
            $table->string('status', 1)->default('Y');
            $table->date('entrydate')->default(now());
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('purchases');
    }
};
