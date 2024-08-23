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
        Schema::create('patient_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('aadhaar_no')->unique()->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->string('phone', 20);
            $table->string('alternate_phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
            $table->foreignId('city_id')->constrained('cities');
            $table->foreignId('state_id')->constrained('states');
            $table->foreignId('country_id')->constrained('countries');
            $table->integer('pincode')->nullable();
            $table->string('marital_status', 20)->nullable();
            $table->integer('visit_count')->default(0);
            $table->string('status', 1)->default('Y');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index('patient_id');
            $table->index('first_name');
            $table->index('aadhaar_no');
        });

        // Create the INSERT trigger
        DB::unprepared('
            CREATE TRIGGER after_patient_profiles_insert
            AFTER INSERT ON patient_profiles
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_profile_audits (
                    patient_id, table_name, action, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.patient_id,
                    "patient_profiles",
                    "INSERT",
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'patient_id: \', NEW.patient_id,
                        \'first_name: \', NEW.first_name,
                        \'last_name: \', NEW.last_name,
                        \'aadhaar_no: \', NEW.aadhaar_no,
                        \'date_of_birth: \', NEW.date_of_birth,
                        \'gender: \', NEW.gender,
                        \'blood_group: \', NEW.blood_group,
                        \'phone: \', NEW.phone,
                        \'alternate_phone: \', NEW.alternate_phone,
                        \'email: \', NEW.email,
                        \'address1: \', NEW.address1,
                        \'address2: \', NEW.address2,
                        \'city_id: \', NEW.city_id,
                        \'state_id: \', NEW.state_id,
                        \'country_id: \', NEW.country_id,
                        \'pincode: \', NEW.pincode,
                        \'marital_status: \', NEW.marital_status,
                        \'visit_count: \', NEW.visit_count,
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
            CREATE TRIGGER after_patient_profiles_update
            AFTER UPDATE ON patient_profiles
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_profile_audits (
                    patient_id, table_name, action, old_data, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.patient_id,
                    "patient_profiles",
                    "UPDATE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'patient_id: \', OLD.patient_id,
                        \'first_name: \', OLD.first_name,
                        \'last_name: \', OLD.last_name,
                        \'aadhaar_no: \', OLD.aadhaar_no,
                        \'date_of_birth: \', OLD.date_of_birth,
                        \'gender: \', OLD.gender,
                        \'blood_group: \', OLD.blood_group,
                        \'phone: \', OLD.phone,
                        \'alternate_phone: \', OLD.alternate_phone,
                        \'email: \', OLD.email,
                        \'address1: \', OLD.address1,
                        \'address2: \', OLD.address2,
                        \'city_id: \', OLD.city_id,
                        \'state_id: \', OLD.state_id,
                        \'country_id: \', OLD.country_id,
                        \'pincode: \', OLD.pincode,
                        \'marital_status: \', OLD.marital_status,
                        \'visit_count: \', OLD.visit_count,
                        \'status: \', OLD.status,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by
                    ),
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'patient_id: \', NEW.patient_id,
                        \'first_name: \', NEW.first_name,
                        \'last_name: \', NEW.last_name,
                        \'aadhaar_no: \', NEW.aadhaar_no,
                        \'date_of_birth: \', NEW.date_of_birth,
                        \'gender: \', NEW.gender,
                        \'blood_group: \', NEW.blood_group,
                        \'phone: \', NEW.phone,
                        \'alternate_phone: \', NEW.alternate_phone,
                        \'email: \', NEW.email,
                        \'address1: \', NEW.address1,
                        \'address2: \', NEW.address2,
                        \'city_id: \', NEW.city_id,
                        \'state_id: \', NEW.state_id,
                        \'country_id: \', NEW.country_id,
                        \'pincode: \', NEW.pincode,
                        \'marital_status: \', NEW.marital_status,
                        \'visit_count: \', NEW.visit_count,
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
            CREATE TRIGGER after_patient_profiles_delete
            AFTER DELETE ON patient_profiles
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_profile_audits (
                    patient_id, table_name, action, old_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.patient_id,
                    "patient_profiles",
                    "DELETE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'patient_id: \', OLD.patient_id,
                        \'first_name: \', OLD.first_name,
                        \'last_name: \', OLD.last_name,
                        \'aadhaar_no: \', OLD.aadhaar_no,
                        \'date_of_birth: \', OLD.date_of_birth,
                        \'gender: \', OLD.gender,
                        \'blood_group: \', OLD.blood_group,
                        \'phone: \', OLD.phone,
                        \'alternate_phone: \', OLD.alternate_phone,
                        \'email: \', OLD.email,
                        \'address1: \', OLD.address1,
                        \'address2: \', OLD.address2,
                        \'city_id: \', OLD.city_id,
                        \'state_id: \', OLD.state_id,
                        \'country_id: \', OLD.country_id,
                        \'pincode: \', OLD.pincode,
                        \'marital_status: \', OLD.marital_status,
                        \'visit_count: \', OLD.visit_count,
                        \'status: \', OLD.status,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by,
                         \'deleted_by: \', OLD.deleted_by
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_profiles_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_profiles_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_profiles_delete');

        // Drop the table
        Schema::dropIfExists('patient_profiles');
    }
};
