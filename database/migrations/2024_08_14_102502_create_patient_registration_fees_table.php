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
        Schema::create('patient_registration_fees', function (Blueprint $table) {
            $table->id();
            $table->string('bill_id')->unique();
            $table->unsignedBigInteger('appointment_id')->nullable()->constrained('appointments');
            $table->unsignedBigInteger('patient_id');
            $table->decimal('amount', 10, 2)->nullable();
            $table->float('tax_percentile')->nullable();
            $table->decimal('tax', 10, 3)->nullable();
            $table->decimal('amount_to_be_paid', 10, 3)->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('gpay', 10, 3)->nullable();
            $table->decimal('cash', 10, 3)->nullable();
            $table->decimal('card', 10, 3)->nullable();
            $table->foreignId('card_pay_id')->nullable()->constrained('card_pays');
            $table->decimal('amount_paid', 10, 3)->nullable();
            $table->decimal('balance_given', 10, 3)->nullable();
            $table->dateTime('bill_paid_date')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->char('status')->default('Y');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('patient_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // Define triggers without using DELIMITER
        DB::unprepared('
            CREATE TRIGGER after_patient_registration_fee_insert
            AFTER INSERT ON patient_registration_fees
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.bill_id,
                    NEW.patient_id,
                    "registration_fee",
                    \'INSERT\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', NEW.bill_id,
                        \'appointment_id: \', NEW.appointment_id,
                        \'patient_id: \', NEW.patient_id,
                        \'amount: \', NEW.amount,
                        \'tax_percentile: \', NEW.tax_percentile,
                        \'tax: \', NEW.tax,
                        \'amount_to_be_paid: \', NEW.amount_to_be_paid,
                        \'payment_method: \', NEW.payment_method,
                        \'gpay: \', NEW.gpay,
                        \'cash: \', NEW.cash,
                        \'card: \', NEW.card,
                        \'card_pay_id: \', NEW.card_pay_id,
                        \'amount_paid: \', NEW.amount_paid,
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
            CREATE TRIGGER after_patient_registration_fee_update
            AFTER UPDATE ON patient_registration_fees
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, old_data, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.bill_id,
                    OLD.patient_id,
                    "registration_fee",
                    \'UPDATE\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', OLD.bill_id,
                        \'appointment_id: \', OLD.appointment_id,
                        \'patient_id: \', OLD.patient_id,
                        \'amount: \', OLD.amount,
                        \'tax_percentile: \', OLD.tax_percentile,
                        \'tax: \', OLD.tax,
                        \'amount_to_be_paid: \', OLD.amount_to_be_paid,
                        \'payment_method: \', OLD.payment_method,
                        \'gpay: \', OLD.gpay,
                        \'cash: \', OLD.cash,
                        \'card: \', OLD.card,
                        \'card_pay_id: \', OLD.card_pay_id,
                        \'amount_paid: \', OLD.amount_paid,
                        \'balance_given: \', OLD.balance_given,
                        \'bill_paid_date: \', OLD.bill_paid_date,
                        \'status: \', OLD.status,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by
                    ),
                    CONCAT_WS(\',\',
                        \'bill_id: \', NEW.bill_id,
                        \'appointment_id: \', NEW.appointment_id,
                        \'patient_id: \', NEW.patient_id,
                        \'amount: \', NEW.amount,
                        \'tax_percentile: \', NEW.tax_percentile,
                        \'tax: \', NEW.tax,
                        \'amount_to_be_paid: \', NEW.amount_to_be_paid,
                        \'payment_method: \', NEW.payment_method,
                        \'gpay: \', NEW.gpay,
                        \'cash: \', NEW.cash,
                        \'card: \', NEW.card,
                        \'card_pay_id: \', NEW.card_pay_id,
                        \'amount_paid: \', NEW.amount_paid,
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
            CREATE TRIGGER after_patient_registration_fee_delete
            AFTER DELETE ON patient_registration_fees
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, old_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.bill_id,
                    OLD.patient_id,
                    "registration_fee",
                    \'DELETE\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', OLD.bill_id,
                        \'appointment_id: \', OLD.appointment_id,
                        \'patient_id: \', OLD.patient_id,
                        \'amount: \', OLD.amount,
                        \'tax_percentile: \', OLD.tax_percentile,
                        \'tax: \', OLD.tax,
                        \'amount_to_be_paid: \', OLD.amount_to_be_paid,
                        \'payment_method: \', OLD.payment_method,
                        \'gpay: \', OLD.gpay,
                        \'cash: \', OLD.cash,
                        \'card: \', OLD.card,
                        \'card_pay_id: \', OLD.card_pay_id,
                        \'amount_paid: \', OLD.amount_paid,
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_registration_fee_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_registration_fee_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_registration_fee_delete');

        // Drop tables
        Schema::dropIfExists('patient_registration_fees');
    }
};
