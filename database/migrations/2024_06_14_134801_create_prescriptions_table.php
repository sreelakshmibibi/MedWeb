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
            $table->foreignId('medid')->constrained('medicines');
            $table->dateTime('endate');
            $table->string('medd_name', 200);
            $table->string('str', 150);
            $table->string('dose', 100);
            $table->string('days', 10);
            $table->string('duration', 20);
            $table->string('remark', 300);
            $table->foreignId('prby')->constrained('users');
            $table->string('finalsave', 5)->default('NO');
            $table->string('status', 5)->default('Y');
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
            $table->index('medid');
            $table->index('medd_name');
            $table->index('str');
            $table->index('dose');
            $table->index('days');
            $table->index('duration');
            $table->index('remark');
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
            $table->dropForeign(['medid']);
            $table->dropForeign(['prby']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
        Schema::dropIfExists('prescriptions');
    }
};
