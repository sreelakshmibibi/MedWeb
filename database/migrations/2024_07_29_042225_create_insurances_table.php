<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->foreignId('insurance_company_id')->constrained('insurance_companies');
            $table->string('policy_number');
            $table->date('policy_end_date');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->string('status', 2)->default('Y')->nullable();
            $table->string('insured_name')->nullable();
            $table->string('insured_dob')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('patient_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // Create the INSERT trigger
        DB::unprepared('
            CREATE TRIGGER after_insurance_insert
            AFTER INSERT ON insurances
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_profile_audits (
                    patient_id, table_name, action, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.patient_id,
                    "insurances",
                    "INSERT",
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'patient_id: \', NEW.patient_id,
                        \'insurance_company_id: \', NEW.insurance_company_id,
                        \'policy_number: \', NEW.policy_number,
                        \'policy_end_date: \', NEW.policy_end_date,
                        \'created_by: \', NEW.created_by,
                        \'updated_by: \', NEW.updated_by,
                        \'status: \', NEW.status,
                        \'insured_name: \', NEW.insured_name,
                        \'insured_dob: \', NEW.insured_dob
                    ),
                    NEW.created_by,
                    NOW(),
                    NOW()
                );
            END
        ');

        // Create the UPDATE trigger
        DB::unprepared('
            CREATE TRIGGER after_insurance_update
            AFTER UPDATE ON insurances
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_profile_audits (
                    patient_id, table_name, action, old_data, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.patient_id,
                    "insurances",
                    "UPDATE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'patient_id: \', OLD.patient_id,
                        \'insurance_company_id: \', OLD.insurance_company_id,
                        \'policy_number: \', OLD.policy_number,
                        \'policy_end_date: \', OLD.policy_end_date,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by,
                        \'status: \', OLD.status,
                        \'insured_name: \', OLD.insured_name,
                        \'insured_dob: \', OLD.insured_dob
                    ),
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'patient_id: \', NEW.patient_id,
                        \'insurance_company_id: \', NEW.insurance_company_id,
                        \'policy_number: \', NEW.policy_number,
                        \'policy_end_date: \', NEW.policy_end_date,
                        \'created_by: \', NEW.created_by,
                        \'updated_by: \', NEW.updated_by,
                        \'status: \', NEW.status,
                        \'insured_name: \', NEW.insured_name,
                        \'insured_dob: \', NEW.insured_dob
                    ),
                    NEW.updated_by,
                    NOW(),
                    NOW()
                );
            END
        ');

        // Create the DELETE trigger
        DB::unprepared('
            CREATE TRIGGER after_insurance_delete
            AFTER DELETE ON insurances
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_profile_audits (
                    patient_id, table_name, action, old_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.patient_id,
                    "insurances",
                    "DELETE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'patient_id: \', OLD.patient_id,
                        \'insurance_company_id: \', OLD.insurance_company_id,
                        \'policy_number: \', OLD.policy_number,
                        \'policy_end_date: \', OLD.policy_end_date,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by,
                        \'status: \', OLD.status,
                        \'insured_name: \', OLD.insured_name,
                        \'insured_dob: \', OLD.insured_dob
                    ),
                    OLD.updated_by,
                    NOW(),
                    NOW()
                );
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared('DROP TRIGGER IF EXISTS after_insurance_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_insurance_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_insurance_delete');

        // Drop tables
        Schema::dropIfExists('insurances');
    }
};
