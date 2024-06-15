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
        Schema::create('treatments', function (Blueprint $table) {
            
            $table->id(); // Primary key
            $table->unsignedBigInteger('patient_id'); // ID of the patient
            $table->unsignedBigInteger('app_id'); // ID of the appointment
            $table->foreignId('treat_id')->constrained('treatment_types');  // ID of the treatment
            $table->integer('qty')->nullable(); // quantity
            $table->string('nursing_remark', 650)->nullable(); // Nursing remarks
            $table->dateTime('treat_date'); // Date of the treatment
            $table->foreignId('doneby')->constrained('users'); // ID of the person who performed the treatment
            $table->string('status', 5)->default('Y'); // Treatment status  
            $table->timestamps(); 

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
            //index
            $table->index('patient_id');
            $table->index('app_id');
            $table->index('doneby');
            $table->index('treat_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            $table->dropForeign(['app_id']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['treat_id']);
            $table->dropForeign(['doneby']);
        });
        Schema::dropIfExists('treatments');
    }
};
