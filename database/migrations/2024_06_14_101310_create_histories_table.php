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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreignId('app_id')->constrained('appointments');
            $table->string('history', 500);
            $table->foreignId('doctor_id')->constrained('users');
            $table->string('status', 5)->default('Y');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            // Indexes
            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('patient_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->index('patient_id');
            $table->index('app_id');
            $table->index('history');
            $table->index('doctor_id');
        });

        // Create the INSERT trigger
        DB::unprepared('
            CREATE TRIGGER after_histories_insert
            AFTER INSERT ON histories
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_profile_audits (
                    patient_id, table_name, action, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.patient_id,
                    "histories",
                    "INSERT",
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'patient_id: \', NEW.patient_id,
                        \'app_id: \', NEW.app_id,
                        \'history: \', NEW.history,
                        \'doctor_id: \', NEW.doctor_id,
                        \'status: \', NEW.status,
                        \'created_by: \', NEW.created_by,
                        \'updated_by: \', NEW.updated_by
                    ),
                    NEW.created_by,
                    NOW(),
                    NOW()
                );
            END
        ');

        // Create the UPDATE trigger
        DB::unprepared('
            CREATE TRIGGER after_histories_update
            AFTER UPDATE ON histories
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_profile_audits (
                    patient_id, table_name, action, old_data, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.patient_id,
                    "histories",
                    "UPDATE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'patient_id: \', OLD.patient_id,
                        \'app_id: \', OLD.app_id,
                        \'history: \', OLD.history,
                        \'doctor_id: \', OLD.doctor_id,
                        \'status: \', OLD.status,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by
                    ),
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'patient_id: \', NEW.patient_id,
                        \'app_id: \', NEW.app_id,
                        \'history: \', NEW.history,
                        \'doctor_id: \', NEW.doctor_id,
                        \'status: \', NEW.status,
                        \'created_by: \', NEW.created_by,
                        \'updated_by: \', NEW.updated_by
                    ),
                    NEW.updated_by,
                    NOW(),
                    NOW()
                );
            END
        ');

        // Create the DELETE trigger
        DB::unprepared('
            CREATE TRIGGER after_histories_delete
            AFTER DELETE ON histories
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_profile_audits (
                    patient_id, table_name, action, old_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.patient_id,
                    "histories",
                    "DELETE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'patient_id: \', OLD.patient_id,
                        \'app_id: \', OLD.app_id,
                        \'history: \', OLD.history,
                        \'doctor_id: \', OLD.doctor_id,
                        \'status: \', OLD.status,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_histories_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_histories_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_histories_delete');

        // Drop the table
        Schema::dropIfExists('histories');
    }
};
