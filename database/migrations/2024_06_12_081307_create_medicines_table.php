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
            $table->string('med_bar_code', 100)->nullable(); // Barcode
            $table->string('med_name', 200); // Medicine name
            $table->string('med_company', 200)->nullable(); // Company name
            $table->integer('stock')->nullable();
            $table->string('stock_status', 20)->nullable(); // Stock status (e.g., 'In Stock', 'Out of Stock')
            $table->string('status', 1)->default('Y'); // Record status (e.g., 'Y' for active, 'N' for inactive)
            $table->longText('med_remarks')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps(); 
            $table->softDeletes(); 
            // Indexes
            $table->index('med_name');
            $table->index('stock');
            $table->index('stock_status');
            $table->index('status');
            
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
