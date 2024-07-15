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
        Schema::create('combo_offer_treatments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('combo_offer_id');
            $table->unsignedBigInteger('treatment_id');

            $table->foreign('combo_offer_id')->references('id')->on('treatment_combo_offers')->onDelete('cascade');
            $table->foreign('treatment_id')->references('id')->on('treatment_types')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_offer_treatments');
    }
};
