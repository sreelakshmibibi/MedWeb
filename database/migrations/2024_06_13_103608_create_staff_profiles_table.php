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
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained('users');
            $table->string('staff_id');
            $table->foreignId('clinic_branch_id')->nullable()->constrained('clinic_branches'); 
            $table->string('aadhaar_no')->unique();
            $table->date('date_of_birth')->nullable(); 
            $table->string('gender', 10)->nullable(); 
            $table->string('phone', 20); 
            $table->text('address1')->nullable(); 
            $table->text('address2')->nullable(); 
            $table->foreignId('city_id')->constrained('cities'); 
            $table->foreignId('state_id')->constrained('states'); 
            $table->foreignId('country_id')->constrained('countries'); 
            $table->string('pincode')->nullable(); 
            
            $table->text('com_address1')->nullable(); //com = communication
            $table->text('com_address2')->nullable(); 
            $table->foreignId('com_city_id')->constrained('cities')->nullable(); 
            $table->foreignId('com_state_id')->constrained('states')->nullable(); 
            $table->foreignId('com_country_id')->constrained('countries')->nullable(); 
            $table->string('com_pincode')->nullable(); 
            
            $table->string('photo', 255)->nullable(); 
            $table->date('date_of_joining');
            $table->date('date_of_relieving')->nullable();
            
            $table->string('qualification');
            $table->string('designation')->nullable();
            $table->foreignId('department_id')->constrained('departments');
            $table->string('specialization')->nullable(); 
            $table->string('years_of_experience')->nullable(); 
            $table->string('license_number', 50)->unique()->nullable(); 
            $table->string('subspecialty')->nullable(); 
            $table->integer('consultation_fees')->nullable(); 
            $table->string('status');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps(); 
            $table->softDeletes(); 
        });

        // Create the INSERT trigger
        DB::unprepared('
            CREATE TRIGGER after_staff_profile_insert
            AFTER INSERT ON staff_profiles
            FOR EACH ROW
            BEGIN
                INSERT INTO user_profile_audits (
                    user_id, table_name, action, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.user_id,
                    "staff_profiles",
                    "INSERT",
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'user_id: \', NEW.user_id,
                        \'staff_id: \', NEW.staff_id,
                        \'clinic_branch_id: \', NEW.clinic_branch_id,
                        \'aadhaar_no: \', NEW.aadhaar_no,
                        \'date_of_birth: \', NEW.date_of_birth,
                        \'gender: \', NEW.gender,
                        \'phone: \', NEW.phone,
                        \'address1: \', NEW.address1,
                        \'address2: \', NEW.address2,
                        \'city_id: \', NEW.city_id,
                        \'state_id: \', NEW.state_id,
                        \'country_id: \', NEW.country_id,
                        \'pincode: \', NEW.pincode,
                        \'com_address1: \', NEW.com_address1,
                        \'com_address2: \', NEW.com_address2,
                        \'com_city_id: \', NEW.com_city_id,
                        \'com_state_id: \', NEW.com_state_id,
                        \'com_country_id: \', NEW.com_country_id,
                        \'com_pincode: \', NEW.com_pincode,
                        \'photo: \', NEW.photo,
                        \'date_of_joining: \', NEW.date_of_joining,
                        \'date_of_relieving: \', NEW.date_of_relieving,
                        \'qualification: \', NEW.qualification,
                        \'designation: \', NEW.designation,
                        \'department_id: \', NEW.department_id,
                        \'specialization: \', NEW.specialization,
                        \'years_of_experience: \', NEW.years_of_experience,
                        \'license_number: \', NEW.license_number,
                        \'subspecialty: \', NEW.subspecialty,
                        \'consultation_fees: \', NEW.consultation_fees,
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
            CREATE TRIGGER after_staff_profile_update
            AFTER UPDATE ON staff_profiles
            FOR EACH ROW
            BEGIN
                INSERT INTO user_profile_audits (
                    user_id, table_name, action, old_data, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.user_id,
                    "staff_profiles",
                    "UPDATE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'user_id: \', OLD.user_id,
                        \'staff_id: \', OLD.staff_id,
                        \'clinic_branch_id: \', OLD.clinic_branch_id,
                        \'aadhaar_no: \', OLD.aadhaar_no,
                        \'date_of_birth: \', OLD.date_of_birth,
                        \'gender: \', OLD.gender,
                        \'phone: \', OLD.phone,
                        \'address1: \', OLD.address1,
                        \'address2: \', OLD.address2,
                        \'city_id: \', OLD.city_id,
                        \'state_id: \', OLD.state_id,
                        \'country_id: \', OLD.country_id,
                        \'pincode: \', OLD.pincode,
                        \'com_address1: \', OLD.com_address1,
                        \'com_address2: \', OLD.com_address2,
                        \'com_city_id: \', OLD.com_city_id,
                        \'com_state_id: \', OLD.com_state_id,
                        \'com_country_id: \', OLD.com_country_id,
                        \'com_pincode: \', OLD.com_pincode,
                        \'photo: \', OLD.photo,
                        \'date_of_joining: \', OLD.date_of_joining,
                        \'date_of_relieving: \', OLD.date_of_relieving,
                        \'qualification: \', OLD.qualification,
                        \'designation: \', OLD.designation,
                        \'department_id: \', OLD.department_id,
                        \'specialization: \', OLD.specialization,
                        \'years_of_experience: \', OLD.years_of_experience,
                        \'license_number: \', OLD.license_number,
                        \'subspecialty: \', OLD.subspecialty,
                        \'consultation_fees: \', OLD.consultation_fees,
                        \'status: \', OLD.status
                    ),
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'user_id: \', NEW.user_id,
                        \'staff_id: \', NEW.staff_id,
                        \'clinic_branch_id: \', NEW.clinic_branch_id,
                        \'aadhaar_no: \', NEW.aadhaar_no,
                        \'date_of_birth: \', NEW.date_of_birth,
                        \'gender: \', NEW.gender,
                        \'phone: \', NEW.phone,
                        \'address1: \', NEW.address1,
                        \'address2: \', NEW.address2,
                        \'city_id: \', NEW.city_id,
                        \'state_id: \', NEW.state_id,
                        \'country_id: \', NEW.country_id,
                        \'pincode: \', NEW.pincode,
                        \'com_address1: \', NEW.com_address1,
                        \'com_address2: \', NEW.com_address2,
                        \'com_city_id: \', NEW.com_city_id,
                        \'com_state_id: \', NEW.com_state_id,
                        \'com_country_id: \', NEW.com_country_id,
                        \'com_pincode: \', NEW.com_pincode,
                        \'photo: \', NEW.photo,
                        \'date_of_joining: \', NEW.date_of_joining,
                        \'date_of_relieving: \', NEW.date_of_relieving,
                        \'qualification: \', NEW.qualification,
                        \'designation: \', NEW.designation,
                        \'department_id: \', NEW.department_id,
                        \'specialization: \', NEW.specialization,
                        \'years_of_experience: \', NEW.years_of_experience,
                        \'license_number: \', NEW.license_number,
                        \'subspecialty: \', NEW.subspecialty,
                        \'consultation_fees: \', NEW.consultation_fees,
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
            CREATE TRIGGER after_staff_profile_delete
            AFTER DELETE ON staff_profiles
            FOR EACH ROW
            BEGIN
                INSERT INTO user_profile_audits (
                    user_id, table_name, action, old_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.user_id,
                    "staff_profiles",
                    "DELETE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'user_id: \', OLD.user_id,
                        \'staff_id: \', OLD.staff_id,
                        \'clinic_branch_id: \', OLD.clinic_branch_id,
                        \'aadhaar_no: \', OLD.aadhaar_no,
                        \'date_of_birth: \', OLD.date_of_birth,
                        \'gender: \', OLD.gender,
                        \'phone: \', OLD.phone,
                        \'address1: \', OLD.address1,
                        \'address2: \', OLD.address2,
                        \'city_id: \', OLD.city_id,
                        \'state_id: \', OLD.state_id,
                        \'country_id: \', OLD.country_id,
                        \'pincode: \', OLD.pincode,
                        \'com_address1: \', OLD.com_address1,
                        \'com_address2: \', OLD.com_address2,
                        \'com_city_id: \', OLD.com_city_id,
                        \'com_state_id: \', OLD.com_state_id,
                        \'com_country_id: \', OLD.com_country_id,
                        \'com_pincode: \', OLD.com_pincode,
                        \'photo: \', OLD.photo,
                        \'date_of_joining: \', OLD.date_of_joining,
                        \'date_of_relieving: \', OLD.date_of_relieving,
                        \'qualification: \', OLD.qualification,
                        \'designation: \', OLD.designation,
                        \'department_id: \', OLD.department_id,
                        \'specialization: \', OLD.specialization,
                        \'years_of_experience: \', OLD.years_of_experience,
                        \'license_number: \', OLD.license_number,
                        \'subspecialty: \', OLD.subspecialty,
                        \'consultation_fees: \', OLD.consultation_fees,
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_staff_profile_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_staff_profile_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_staff_profile_delete');

        Schema::table('staff_profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['country_id']);
        });

        Schema::dropIfExists('staff_profiles');
    }
};
