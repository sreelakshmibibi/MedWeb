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
            $table->string('invoice_no');
            $table->date('invoice_date');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->enum('category', ['D', 'C'])->comment('D - Debit, C - Credit');
            $table->decimal('item_subtotal');
            $table->decimal('delivery_charge');
            $table->decimal('gst');
            $table->decimal('total_currentbill');
            $table->decimal('discount');
            $table->decimal('previous_due')->nullable();
            $table->decimal('amount_to_be_paid');
            $table->decimal('gpay');
            $table->decimal('cash');
            $table->decimal('card');
            $table->decimal('amount_paid');
            $table->decimal('balance_due');
            $table->decimal('balance_to_give_back');
            $table->string('balance_given', 1);
            $table->string('consider_for_next_payment', 1);
            $table->string('billfile', 255)->nullable();
            $table->string('status', 1)->default('Y');
            $table->date('entrydate')->default(now());
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
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
