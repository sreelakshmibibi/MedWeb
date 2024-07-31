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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreignId('app_id')->constrained('appointments');
            $table->foreignId('medicine_id')->constrained('medicines')->nullable();
            $table->string('medicine_name', 200)->nullable();
            $table->string('medicine_company', 150)->nullable();
            $table->foreignId('dosage_id')->constrained('dosages')->nullable();
            $table->unsignedInteger('duration');
            $table->string('advice', 50)->nullable();
            $table->string('remark', 300)->nullable();
            $table->foreignId('prescribed_by')->constrained('users');
            $table->string('finalsave', 5)->default('NO')->nullable();
            $table->string('status', 5)->default('Y')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('patient_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Indexes
            $table->index('patient_id');
            $table->index('app_id');
            $table->index('medicine_id');
            $table->index('medicine_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['app_id']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
        Schema::dropIfExists('prescriptions');
    }
};
