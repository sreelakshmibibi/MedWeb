<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the tooth_examinations table
        Schema::create('tooth_examinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreignId('app_id')->constrained('appointments');
            $table->string('tooth_id')->nullable();
            $table->unsignedBigInteger('row_id')->nullable();
            $table->string('face_part')->nullable();
            $table->foreignId('tooth_score_id')->nullable()->constrained('tooth_scores');
            $table->string('chief_complaint');
            $table->string('hpi')->nullable();
            $table->string('dental_examination');
            $table->string('diagnosis');
            $table->foreignId('disease_id')->nullable()->constrained('diseases');
            $table->foreignId('treatment_id')->constrained('treatment_types');
            $table->foreignId('treatment_plan_id')->nullable()->constrained('treatment_plans');
            $table->foreignId('shade_id')->nullable()->constrained('shades');
            $table->string('upper_shade')->nullable();
            $table->string('middle_shade')->nullable();
            $table->string('lower_shade')->nullable();
            $table->string('metal_trial')->nullable();
            $table->string('bisq_trial')->nullable();
            $table->string('finish')->nullable();
            $table->string('instructions')->nullable();
            $table->string('xray')->nullable();
            $table->string('is_xray_billable')->default('N');
            $table->foreignId('lingual_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('labial_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('occulusal_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('distal_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('mesial_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('palatal_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('buccal_condn')->nullable()->constrained('surface_conditions');
            $table->foreignId('treatment_status')->constrained('treatment_statuses');
            $table->string('anatomy_image')->nullable();
            $table->string('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->string('status', 5)->default('Y');

            $table->foreign('patient_id')->references('patient_id')->on('patient_profiles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tooth_id')->references('teeth_name')->on('teeths')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('row_id')->references('id')->on('teeth_rows')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        // Create the audit table for tooth_examinations
        Schema::create('tooth_examination_audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tooth_examination_id');
            $table->string('action');
            $table->text('old_data')->nullable();
            $table->text('new_data')->nullable();
            $table->unsignedBigInteger('changed_by');
            $table->timestamps();
        });

        // Create the INSERT trigger
        DB::unprepared('
            CREATE TRIGGER after_tooth_examination_insert
            AFTER INSERT ON tooth_examinations
            FOR EACH ROW
            BEGIN
                INSERT INTO tooth_examination_audits (
                    tooth_examination_id, action, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.id,
                    "INSERT",
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'patient_id: \', NEW.patient_id,
                        \'app_id: \', NEW.app_id,
                        \'tooth_id: \', NEW.tooth_id,
                        \'row_id: \', NEW.row_id,
                        \'tooth_score_id: \', NEW.tooth_score_id,
                        \'chief_complaint: \', NEW.chief_complaint,
                        \'hpi: \', NEW.hpi,
                        \'dental_examination: \', NEW.dental_examination,
                        \'diagnosis: \', NEW.diagnosis,
                        \'disease_id: \', NEW.disease_id,
                        \'treatment_id: \', NEW.treatment_id,
                        \'treatment_plan_id: \', NEW.treatment_plan_id,
                        \'xray: \', NEW.xray,
                        \'lingual_condn: \', NEW.lingual_condn,
                        \'labial_condn: \', NEW.labial_condn,
                        \'occulusal_condn: \', NEW.occulusal_condn,
                        \'distal_condn: \', NEW.distal_condn,
                        \'mesial_condn: \', NEW.mesial_condn,
                        \'palatal_condn: \', NEW.palatal_condn,
                        \'buccal_condn: \', NEW.buccal_condn,
                        \'treatment_status: \', NEW.treatment_status,
                        \'anatomy_image: \', NEW.anatomy_image,
                        \'remarks: \', NEW.remarks,
                        \'created_by: \', NEW.created_by,
                        \'updated_by: \', NEW.updated_by,
                        \'status: \', NEW.status
                    ),
                    NEW.created_by,
                    NOW(),
                    NOW()
                );
            END
        ');

        // Create the UPDATE trigger
        DB::unprepared('
            CREATE TRIGGER after_tooth_examination_update
            AFTER UPDATE ON tooth_examinations
            FOR EACH ROW
            BEGIN
                INSERT INTO tooth_examination_audits (
                    tooth_examination_id, action, old_data, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.id,
                    "UPDATE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'patient_id: \', OLD.patient_id,
                        \'app_id: \', OLD.app_id,
                        \'tooth_id: \', OLD.tooth_id,
                        \'row_id: \', OLD.row_id,
                        \'tooth_score_id: \', OLD.tooth_score_id,
                        \'chief_complaint: \', OLD.chief_complaint,
                        \'hpi: \', OLD.hpi,
                        \'dental_examination: \', OLD.dental_examination,
                        \'diagnosis: \', OLD.diagnosis,
                        \'disease_id: \', OLD.disease_id,
                        \'treatment_id: \', OLD.treatment_id,
                        \'treatment_plan_id: \', OLD.treatment_plan_id,
                        \'xray: \', OLD.xray,
                        \'lingual_condn: \', OLD.lingual_condn,
                        \'labial_condn: \', OLD.labial_condn,
                        \'occulusal_condn: \', OLD.occulusal_condn,
                        \'distal_condn: \', OLD.distal_condn,
                        \'mesial_condn: \', OLD.mesial_condn,
                        \'palatal_condn: \', OLD.palatal_condn,
                        \'buccal_condn: \', OLD.buccal_condn,
                        \'treatment_status: \', OLD.treatment_status,
                        \'anatomy_image: \', OLD.anatomy_image,
                        \'remarks: \', OLD.remarks,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by,
                        \'status: \', OLD.status
                    ),
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'patient_id: \', NEW.patient_id,
                        \'app_id: \', NEW.app_id,
                        \'tooth_id: \', NEW.tooth_id,
                        \'row_id: \', NEW.row_id,
                        \'tooth_score_id: \', NEW.tooth_score_id,
                        \'chief_complaint: \', NEW.chief_complaint,
                        \'hpi: \', NEW.hpi,
                        \'dental_examination: \', NEW.dental_examination,
                        \'diagnosis: \', NEW.diagnosis,
                        \'disease_id: \', NEW.disease_id,
                        \'treatment_id: \', NEW.treatment_id,
                        \'treatment_plan_id: \', NEW.treatment_plan_id,
                        \'xray: \', NEW.xray,
                        \'lingual_condn: \', NEW.lingual_condn,
                        \'labial_condn: \', NEW.labial_condn,
                        \'occulusal_condn: \', NEW.occulusal_condn,
                        \'distal_condn: \', NEW.distal_condn,
                        \'mesial_condn: \', NEW.mesial_condn,
                        \'palatal_condn: \', NEW.palatal_condn,
                        \'buccal_condn: \', NEW.buccal_condn,
                        \'treatment_status: \', NEW.treatment_status,
                        \'anatomy_image: \', NEW.anatomy_image,
                        \'remarks: \', NEW.remarks,
                        \'created_by: \', NEW.created_by,
                        \'updated_by: \', NEW.updated_by,
                        \'status: \', NEW.status
                    ),
                    NEW.updated_by,
                    NOW(),
                    NOW()
                );
            END
        ');

        // Create the DELETE trigger
        DB::unprepared('
            CREATE TRIGGER after_tooth_examination_delete
            AFTER DELETE ON tooth_examinations
            FOR EACH ROW
            BEGIN
                INSERT INTO tooth_examination_audits (
                    tooth_examination_id, action, old_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.id,
                    "DELETE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'patient_id: \', OLD.patient_id,
                        \'app_id: \', OLD.app_id,
                        \'tooth_id: \', OLD.tooth_id,
                        \'row_id: \', OLD.row_id,
                        \'tooth_score_id: \', OLD.tooth_score_id,
                        \'chief_complaint: \', OLD.chief_complaint,
                        \'hpi: \', OLD.hpi,
                        \'dental_examination: \', OLD.dental_examination,
                        \'diagnosis: \', OLD.diagnosis,
                        \'disease_id: \', OLD.disease_id,
                        \'treatment_id: \', OLD.treatment_id,
                        \'treatment_plan_id: \', OLD.treatment_plan_id,
                        \'xray: \', OLD.xray,
                        \'lingual_condn: \', OLD.lingual_condn,
                        \'labial_condn: \', OLD.labial_condn,
                        \'occulusal_condn: \', OLD.occulusal_condn,
                        \'distal_condn: \', OLD.distal_condn,
                        \'mesial_condn: \', OLD.mesial_condn,
                        \'palatal_condn: \', OLD.palatal_condn,
                        \'buccal_condn: \', OLD.buccal_condn,
                        \'treatment_status: \', OLD.treatment_status,
                        \'anatomy_image: \', OLD.anatomy_image,
                        \'remarks: \', OLD.remarks,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by,
                        \'status: \', OLD.status
                    ),
                    OLD.deleted_by,
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_tooth_examination_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_tooth_examination_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_tooth_examination_delete');

        // Drop the tooth_examination_audits table
        Schema::dropIfExists('tooth_examination_audits');

        // Drop the tooth_examinations table
        Schema::dropIfExists('tooth_examinations');
    }
};
