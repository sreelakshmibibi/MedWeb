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
        Schema::create('treatment_combo_offers', function (Blueprint $table) {

            $table->id();
            $table->decimal('offer_amount', 10, 3); // Offer amount
            $table->date('offer_from')->nullable(); //  Offer start date
            $table->date('offer_to')->nullable(); //  Offer end date
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->string('status', 5)->default('Y');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_combo_offers');
    }
};