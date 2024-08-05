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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->string('policy_holder_type')->nullable(); //['policy_holder', 'responsible_party']
            // Primary Insurance Details
            $table->string('is_primary_insurance', 2)->default('N')->nullable();
            $table->string('prim_ins_id')->nullable();
            $table->string('prim_ins_insured_name')->nullable();
            $table->string('prim_ins_insured_dob')->nullable();
            $table->foreignId('prim_ins_company_id')->nullable()->constrained('insurance_companies');
            $table->string('prim_ins_company')->nullable();
            $table->string('prim_ins_com_address')->nullable();
            $table->string('prim_ins_group_name')->nullable();
            $table->string('prim_ins_group_number')->nullable();
            $table->date('prim_ins_policy_start_date')->nullable();
            $table->date('prim_ins_policy_end_date')->nullable();
            $table->string('prim_ins_relation_to_insured')->nullable();
            // Secondary Insurance Details
            $table->string('is_secondary_insurance', 2)->default('N')->nullable();
            $table->string('sec_ins_id')->nullable();
            $table->string('sec_ins_insured_name')->nullable();
            $table->string('sec_ins_insured_dob')->nullable();
            $table->foreignId('sec_ins_company_id')->nullable()->constrained('insurance_companies');
            $table->string('sec_ins_company')->nullable();
            $table->string('sec_ins_com_address')->nullable();
            $table->string('sec_ins_group_name')->nullable();
            $table->string('sec_ins_group_number')->nullable();
            $table->date('sec_ins_policy_start_date')->nullable();
            $table->date('sec_ins_policy_end_date')->nullable();
            $table->string('sec_ins_relation_to_insured')->nullable();
            $table->string('status', 2)->default('Y')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('patient_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
