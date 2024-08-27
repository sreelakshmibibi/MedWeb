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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('is_admin')->nullable()->default(0);
            $table->integer('is_doctor')->nullable()->default(0);
            $table->integer('is_nurse')->nullable()->default(0);
            $table->integer('is_reception')->nullable()->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); 
        });
        
        // Create triggers after the table has been created
        DB::unprepared('
            CREATE TRIGGER after_user_insert
            AFTER INSERT ON users
            FOR EACH ROW
            BEGIN
                INSERT INTO user_profile_audits (
                    user_id, table_name, action, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.id,
                    "users",
                    "INSERT",
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'name: \', NEW.name,
                        \'email: \', NEW.email,
                        \'password: \', NEW.password,
                        \'is_admin: \', NEW.is_admin,
                        \'is_doctor: \', NEW.is_doctor,
                        \'is_nurse: \', NEW.is_nurse,
                        \'is_reception: \', NEW.is_reception,
                        \'created_by: \', NEW.created_by,
                        \'updated_by: \', NEW.updated_by
                    ),
                    NEW.created_by,
                    NOW(),
                    NOW()
                );
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_user_update
            AFTER UPDATE ON users
            FOR EACH ROW
            BEGIN
                INSERT INTO user_profile_audits (
                    user_id, table_name, action, old_data, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.id,
                    "users",
                    "UPDATE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'name: \', OLD.name,
                        \'email: \', OLD.email,
                        \'password: \', OLD.password,
                        \'is_admin: \', OLD.is_admin,
                        \'is_doctor: \', OLD.is_doctor,
                        \'is_nurse: \', OLD.is_nurse,
                        \'is_reception: \', OLD.is_reception,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by
                    ),
                    CONCAT_WS(\',\',
                        \'id: \', NEW.id,
                        \'name: \', NEW.name,
                        \'email: \', NEW.email,
                        \'password: \', NEW.password,
                        \'is_admin: \', NEW.is_admin,
                        \'is_doctor: \', NEW.is_doctor,
                        \'is_nurse: \', NEW.is_nurse,
                        \'is_reception: \', NEW.is_reception,
                        \'created_by: \', NEW.created_by,
                        \'updated_by: \', NEW.updated_by
                    ),
                    NEW.updated_by,
                    NOW(),
                    NOW()
                );
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_user_delete
            AFTER DELETE ON users
            FOR EACH ROW
            BEGIN
                INSERT INTO user_profile_audits (
                    user_id, table_name, action, old_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.id,
                    "users",
                    "DELETE",
                    CONCAT_WS(\',\',
                        \'id: \', OLD.id,
                        \'name: \', OLD.name,
                        \'email: \', OLD.email,
                        \'password: \', OLD.password,
                        \'is_admin: \', OLD.is_admin,
                        \'is_doctor: \', OLD.is_doctor,
                        \'is_nurse: \', OLD.is_nurse,
                        \'is_reception: \', OLD.is_reception,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by
                    ),
                    OLD.updated_by,
                    NOW(),
                    NOW()
                );
            END
        ');

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_user_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_user_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_user_delete');

        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
