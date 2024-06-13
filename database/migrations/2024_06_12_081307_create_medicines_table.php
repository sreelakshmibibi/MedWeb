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
            $table->id();
            $table->string('med_bar_code', 100);
            $table->string('med_name', 200);
            $table->string('med_strength', 50);
            $table->longText('med_remarks');
            $table->decimal('med_price', 10, 3);
            $table->string('med_status', 50);
            $table->string('status', 5)->default('Y');
            $table->date('med_date');
            $table->date('med_last_update');
            $table->timestamps();

            $table->index('med_name');
            $table->index('med_strength');
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
