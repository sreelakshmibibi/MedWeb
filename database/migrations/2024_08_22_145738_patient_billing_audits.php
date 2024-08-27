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
        Schema::create('patient_billing_audits', function (Blueprint $table) {
            $table->id();
            $table->integer('billing_id');
            $table->unsignedBigInteger('patient_id');
            $table->string('billing_type');
            $table->enum('action', ['INSERT', 'UPDATE', 'DELETE']);
            $table->text('old_data')->nullable();
            $table->text('new_data')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamps();

            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('patient_id')->references('patient_id')->on('patient_profiles')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_billing_audits');
        
    }
};
