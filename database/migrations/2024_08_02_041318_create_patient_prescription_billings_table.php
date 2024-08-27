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
        Schema::create('patient_prescription_billings', function (Blueprint $table) {
            $table->id();
            $table->string('bill_id')->unique();
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('patient_id');
            $table->decimal('prescription_total_amount', 10, 3)->nullable();
            $table->float('tax_percentile')->nullable();
            $table->decimal('tax', 10, 3)->nullable();
            $table->decimal('discount', 10, 3)->nullable();
            $table->decimal('amount_to_be_paid', 10, 3)->nullable();
            $table->decimal('gpay', 10, 3)->nullable();
            $table->decimal('cash', 10, 3)->nullable();
            $table->decimal('card', 10, 3)->nullable();
            $table->foreignId('card_pay_id')->nullable()->constrained('card_pays');
            $table->decimal('bank_tax', 10, 3)->nullable();
            $table->decimal('amount_paid', 10, 3)->nullable();
            $table->decimal('balance_given', 10, 3)->nullable();
            $table->dateTime('bill_paid_date')->nullable();
            $table->char('status')->default('Y');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('patient_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('cascade');
        });

        // Define triggers without using DELIMITER
        DB::unprepared('
            CREATE TRIGGER after_patient_prescription_billing_insert
            AFTER INSERT ON patient_prescription_billings
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.id,
                    NEW.patient_id,
                    "prescription",
                    \'INSERT\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', NEW.bill_id,
                        \'appointment_id: \', NEW.appointment_id,
                        \'patient_id: \', NEW.patient_id,
                        \'prescription_total_amount: \', NEW.prescription_total_amount,
                        \'tax_percentile: \', NEW.tax_percentile,
                        \'tax: \', NEW.tax,
                        \'discount: \', NEW.discount,
                        \'amount_to_be_paid: \', NEW.amount_to_be_paid,
                        \'gpay: \', NEW.gpay,
                        \'cash: \', NEW.cash,
                        \'card: \', NEW.card,
                        \'card_pay_id: \', NEW.card_pay_id,
                        \'bank_tax: \', NEW.bank_tax,
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
            CREATE TRIGGER after_patient_prescription_billing_update
            AFTER UPDATE ON patient_prescription_billings
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, old_data, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.id,
                    OLD.patient_id,
                    "prescription",
                    \'UPDATE\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', OLD.bill_id,
                        \'appointment_id: \', OLD.appointment_id,
                        \'patient_id: \', OLD.patient_id,
                        \'prescription_total_amount: \', OLD.prescription_total_amount,
                        \'tax_percentile: \', OLD.tax_percentile,
                        \'tax: \', OLD.tax,
                        \'discount: \', OLD.discount,
                        \'amount_to_be_paid: \', OLD.amount_to_be_paid,
                        \'gpay: \', OLD.gpay,
                        \'cash: \', OLD.cash,
                        \'card: \', OLD.card,
                        \'card_pay_id: \', OLD.card_pay_id,
                        \'bank_tax: \', OLD.bank_tax,
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
                        \'prescription_total_amount: \', NEW.prescription_total_amount,
                        \'tax_percentile: \', NEW.tax_percentile,
                        \'tax: \', NEW.tax,
                        \'discount: \', NEW.discount,
                        \'amount_to_be_paid: \', NEW.amount_to_be_paid,
                        \'gpay: \', NEW.gpay,
                        \'cash: \', NEW.cash,
                        \'card: \', NEW.card,
                        \'card_pay_id: \', NEW.card_pay_id,
                        \'bank_tax: \', NEW.bank_tax,
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
            CREATE TRIGGER after_patient_prescription_billing_delete
            AFTER DELETE ON patient_prescription_billings
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, old_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.id,
                    OLD.patient_id,
                    "prescription",
                    \'DELETE\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', OLD.bill_id,
                        \'appointment_id: \', OLD.appointment_id,
                        \'patient_id: \', OLD.patient_id,
                        \'prescription_total_amount: \', OLD.prescription_total_amount,
                        \'tax_percentile: \', OLD.tax_percentile,
                        \'tax: \', OLD.tax,
                        \'discount: \', OLD.discount,
                        \'amount_to_be_paid: \', OLD.amount_to_be_paid,
                        \'gpay: \', OLD.gpay,
                        \'cash: \', OLD.cash,
                        \'card: \', OLD.card,
                        \'card_pay_id: \', OLD.card_pay_id,
                        \'bank_tax: \', OLD.bank_tax,
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_prescription_billing_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_prescription_billing_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_prescription_billing_delete');

        // Drop tables
        Schema::dropIfExists('patient_prescription_billings');
    }
};
