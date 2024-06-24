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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('med_bar_code', 100); // Barcode
            $table->string('med_name', 200); // Medicine name
            $table->string('med_company', 200); // Company name
            $table->longText('med_remarks')->nullable(); // Additional remarks about the medicine
            $table->decimal('med_price', 10, 2); // Price of the medicine
            $table->date('expiry_date'); // Expiry date of the medicine
            $table->integer('units_per_package'); // number of units (tablets, pills, etc.) per package
            $table->integer('package_count'); // Number of packages
            $table->integer('total_quantity'); // Total number of units available across all packages
            $table->string('package_type', 50); // field helps distinguish between strips, bottles, and other packaging types
            $table->string('stock_status', 20); // Stock status (e.g., 'In Stock', 'Out of Stock')
            $table->string('status', 5)->default('Y'); // Record status (e.g., 'Y' for active, 'N' for inactive)
            $table->timestamps(); // Timestamps for created_at and updated_at
            $table->softDeletes(); // Soft delete column (deleted_at)

            // Indexes
            $table->index('med_name');
            $table->index('units_per_package');
            $table->index('status');
            $table->index('expiry_date');
            $table->index('total_quantity');
            $table->index('stock_status');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
