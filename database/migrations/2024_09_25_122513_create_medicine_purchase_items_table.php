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
        Schema::create('medicine_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained('medicines');
            $table->string('batch_no', 100)->nullable();
            $table->decimal('med_price', 10, 2)->nullable(); // Price of the medicine
            $table->date('expiry_date')->nullable(); // Expiry date of the medicine
            $table->integer('units_per_package')->nullable(); // number of units (tablets, pills, etc.) per package
            $table->integer('package_count')->nullable(); // Number of packages
            $table->integer('total_quantity')->nullable(); // Total number of units available across all packages
            $table->string('package_type', 50)->nullable(); // field helps distinguish between strips, bottles, and other packaging types
            $table->decimal('purchase_unit_price',10)->nullable();
            $table->decimal('purchase_amount',10)->nullable();
            $table->string('status', 1)->default('Y');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->index('med_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_purchase_items');
    }
};
