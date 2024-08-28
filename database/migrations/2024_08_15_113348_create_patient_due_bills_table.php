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
        Schema::create('patient_due_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_id')->unique();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('treatment_bill_id')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('gpay', 10, 3)->nullable();
            $table->decimal('cash', 10, 3)->nullable();
            $table->decimal('card', 10, 3)->nullable();
            $table->foreignId('card_pay_id')->nullable()->constrained('card_pays');
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance_given', 10, 3)->nullable();
            $table->dateTime('bill_paid_date')->nullable();
            $table->char('status', 1)->default('Y');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('patient_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('set null');
            $table->foreign('treatment_bill_id')->references('id')->on('patient_treatment_billings')->onDelete('set null');
        });

        // Define triggers
        DB::unprepared('
            CREATE TRIGGER after_patient_due_bill_insert
            AFTER INSERT ON patient_due_bills
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.bill_id,
                    NEW.patient_id,
                    "due_bill",
                    \'INSERT\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', NEW.bill_id,
                        \'patient_id: \', NEW.patient_id,
                        \'appointment_id: \', NEW.appointment_id,
                        \'treatment_bill_id: \', NEW.treatment_bill_id,
                        \'total_amount: \', NEW.total_amount,
                        \'gpay: \', NEW.gpay,
                        \'cash: \', NEW.cash,
                        \'card: \', NEW.card,
                        \'card_pay_id: \', NEW.card_pay_id,
                        \'paid_amount: \', NEW.paid_amount,
                        \'balance_given: \', NEW.balance_given,
                        \'bill_paid_date: \', NEW.bill_paid_date,
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

        DB::unprepared('
            CREATE TRIGGER after_patient_due_bill_update
            AFTER UPDATE ON patient_due_bills
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, old_data, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.bill_id,
                    OLD.patient_id,
                    "due_bill",
                    \'UPDATE\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', OLD.bill_id,
                        \'patient_id: \', OLD.patient_id,
                        \'appointment_id: \', OLD.appointment_id,
                        \'treatment_bill_id: \', OLD.treatment_bill_id,
                        \'total_amount: \', OLD.total_amount,
                        \'gpay: \', OLD.gpay,
                        \'cash: \', OLD.cash,
                        \'card: \', OLD.card,
                        \'card_pay_id: \', OLD.card_pay_id,
                        \'paid_amount: \', OLD.paid_amount,
                        \'balance_given: \', OLD.balance_given,
                        \'bill_paid_date: \', OLD.bill_paid_date,
                        \'status: \', OLD.status,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by
                    ),
                    CONCAT_WS(\',\',
                        \'bill_id: \', NEW.bill_id,
                        \'patient_id: \', NEW.patient_id,
                        \'appointment_id: \', NEW.appointment_id,
                        \'treatment_bill_id: \', NEW.treatment_bill_id,
                        \'total_amount: \', NEW.total_amount,
                        \'gpay: \', NEW.gpay,
                        \'cash: \', NEW.cash,
                        \'card: \', NEW.card,
                        \'card_pay_id: \', NEW.card_pay_id,
                        \'paid_amount: \', NEW.paid_amount,
                        \'balance_given: \', NEW.balance_given,
                        \'bill_paid_date: \', NEW.bill_paid_date,
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

        DB::unprepared('
            CREATE TRIGGER after_patient_due_bill_delete
            AFTER DELETE ON patient_due_bills
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, old_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.bill_id,
                    OLD.patient_id,
                    "due_bill",
                    \'DELETE\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', OLD.bill_id,
                        \'patient_id: \', OLD.patient_id,
                        \'appointment_id: \', OLD.appointment_id,
                        \'treatment_bill_id: \', OLD.treatment_bill_id,
                        \'total_amount: \', OLD.total_amount,
                        \'gpay: \', OLD.gpay,
                        \'cash: \', OLD.cash,
                        \'card: \', OLD.card,
                        \'card_pay_id: \', OLD.card_pay_id,
                        \'paid_amount: \', OLD.paid_amount,
                        \'balance_given: \', OLD.balance_given,
                        \'bill_paid_date: \', OLD.bill_paid_date,
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_due_bill_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_due_bill_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_due_bill_delete');

        // Drop tables
        Schema::dropIfExists('patient_due_bills');
    }
};
