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
        Schema::create('advice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('app_id');
            $table->string('advice', 500);
            $table->foreignId('doctor_id')->constrained('doctor_profiles');
            $table->dateTime('cdate');
            $table->string('status', 5)->default('Y');
            $table->timestamp('updt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('patient_id')
                  ->references('patient_id')
                  ->on('patient_profiles')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('app_id')
                  ->references('app_id')
                  ->on('appoinments')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            // Indexes
            $table->index('patient_id');
            $table->index('app_id');
            $table->index('advice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advice', function (Blueprint $table) {
            $table->dropForeign(['app_id']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
        });
        Schema::dropIfExists('advice');
    }
};
