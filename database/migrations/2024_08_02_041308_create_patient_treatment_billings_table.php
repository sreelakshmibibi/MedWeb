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
        // Create patient_treatment_billings table
        Schema::create('patient_treatment_billings', function (Blueprint $table) {
            $table->id();
            $table->string('bill_id')->unique();
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('patient_id');
            $table->decimal('treatment_total_amount', 10, 3)->nullable();
            $table->decimal('combo_offer_deduction', 10, 3)->nullable();
            $table->decimal('previous_outstanding', 10, 3)->nullable();
            $table->decimal('doctor_discount', 10, 3)->nullable();
            $table->decimal('insurance_paid', 10, 3)->nullable();
            $table->float('tax_percentile')->nullable();
            $table->decimal('tax', 10, 3)->nullable();
            $table->decimal('amount_to_be_paid', 10, 3)->nullable();
            $table->decimal('gpay', 10, 3)->nullable();
            $table->decimal('cash', 10, 3)->nullable();
            $table->decimal('card', 10, 3)->nullable();
            $table->unsignedBigInteger('card_pay_id')->nullable();
            $table->decimal('bank_tax', 10, 3)->nullable();
            $table->decimal('amount_paid', 10, 3)->nullable();
            $table->decimal('balance_due', 10, 3)->nullable();
            $table->decimal('balance_to_give_back', 10, 3)->nullable();
            $table->char('balance_given')->nullable();
            $table->char('consider_for_next_payment')->nullable();
            $table->integer('bill_status')->nullable();
            $table->string('bill_delete_reason')->nullable();
            $table->dateTime('bill_paid_date')->nullable();
            $table->string('due_covered_bill_no')->nullable();
            $table->dateTime('due_covered_date')->nullable();
            $table->char('status')->default('Y');
            $table->unsignedBigInteger('billed_by')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->foreign('patient_id')->references('patient_id')->on('patient_profiles')->onDelete('cascade');
            $table->foreign('card_pay_id')->references('id')->on('card_pays')->onDelete('set null');
            $table->foreign('billed_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

       
        // Define triggers without using DELIMITER
        DB::unprepared('
            CREATE TRIGGER after_patient_treatment_billing_insert
            AFTER INSERT ON patient_treatment_billings
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    NEW.id,
                    NEW.patient_id,
                    "treatment",
                    \'INSERT\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', NEW.bill_id,
                        \'appointment_id: \', NEW.appointment_id,
                        \'patient_id: \', NEW.patient_id,
                        \'treatment_total_amount: \', NEW.treatment_total_amount,
                        \'combo_offer_deduction: \', NEW.combo_offer_deduction,
                        \'previous_outstanding: \', NEW.previous_outstanding,
                        \'doctor_discount: \', NEW.doctor_discount,
                        \'insurance_paid: \', NEW.insurance_paid,
                        \'tax_percentile: \', NEW.tax_percentile,
                        \'tax: \', NEW.tax,
                        \'amount_to_be_paid: \', NEW.amount_to_be_paid,
                        \'gpay: \', NEW.gpay,
                        \'cash: \', NEW.cash,
                        \'card: \', NEW.card,
                        \'card_pay_id: \', NEW.card_pay_id,
                        \'bank_tax: \', NEW.bank_tax,
                        \'amount_paid: \', NEW.amount_paid,
                        \'balance_due: \', NEW.balance_due,
                        \'balance_to_give_back: \', NEW.balance_to_give_back,
                        \'balance_given: \', NEW.balance_given,
                        \'consider_for_next_payment: \', NEW.consider_for_next_payment,
                        \'bill_status: \', NEW.bill_status,
                        \'bill_delete_reason: \', NEW.bill_delete_reason,
                        \'bill_paid_date: \', NEW.bill_paid_date,
                        \'due_covered_bill_no: \', NEW.due_covered_bill_no,
                        \'due_covered_date: \', NEW.due_covered_date,
                        \'status: \', NEW.status,
                        \'billed_by: \', NEW.billed_by,
                        \'created_by: \', NEW.created_by,
                        \'updated_by: \', NEW.updated_by,
                        \'deleted_by: \', NEW.deleted_by
                    ),
                    New.created_by,
                    NOW(),
                    NOW()
                );
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_patient_treatment_billing_update
            AFTER UPDATE ON patient_treatment_billings
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, old_data, new_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.id,
                    OLD.patient_id,
                    "treatment",
                    \'UPDATE\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', OLD.bill_id,
                        \'appointment_id: \', OLD.appointment_id,
                        \'patient_id: \', OLD.patient_id,
                        \'treatment_total_amount: \', OLD.treatment_total_amount,
                        \'combo_offer_deduction: \', OLD.combo_offer_deduction,
                        \'previous_outstanding: \', OLD.previous_outstanding,
                        \'doctor_discount: \', OLD.doctor_discount,
                        \'insurance_paid: \', OLD.insurance_paid,
                        \'tax_percentile: \', OLD.tax_percentile,
                        \'tax: \', OLD.tax,
                        \'amount_to_be_paid: \', OLD.amount_to_be_paid,
                        \'gpay: \', OLD.gpay,
                        \'cash: \', OLD.cash,
                        \'card: \', OLD.card,
                        \'card_pay_id: \', OLD.card_pay_id,
                        \'bank_tax: \', OLD.bank_tax,
                        \'amount_paid: \', OLD.amount_paid,
                        \'balance_due: \', OLD.balance_due,
                        \'balance_to_give_back: \', OLD.balance_to_give_back,
                        \'balance_given: \', OLD.balance_given,
                        \'consider_for_next_payment: \', OLD.consider_for_next_payment,
                        \'bill_status: \', OLD.bill_status,
                        \'bill_delete_reason: \', OLD.bill_delete_reason,
                        \'bill_paid_date: \', OLD.bill_paid_date,
                        \'due_covered_bill_no: \', OLD.due_covered_bill_no,
                        \'due_covered_date: \', OLD.due_covered_date,
                        \'status: \', OLD.status,
                        \'billed_by: \', OLD.billed_by,
                        \'created_by: \', OLD.created_by,
                        \'updated_by: \', OLD.updated_by,
                        \'deleted_by: \', OLD.deleted_by
                    ),
                    CONCAT_WS(\',\',
                        \'bill_id: \', NEW.bill_id,
                        \'appointment_id: \', NEW.appointment_id,
                        \'patient_id: \', NEW.patient_id,
                        \'treatment_total_amount: \', NEW.treatment_total_amount,
                        \'combo_offer_deduction: \', NEW.combo_offer_deduction,
                        \'previous_outstanding: \', NEW.previous_outstanding,
                        \'doctor_discount: \', NEW.doctor_discount,
                        \'insurance_paid: \', NEW.insurance_paid,
                        \'tax_percentile: \', NEW.tax_percentile,
                        \'tax: \', NEW.tax,
                        \'amount_to_be_paid: \', NEW.amount_to_be_paid,
                        \'gpay: \', NEW.gpay,
                        \'cash: \', NEW.cash,
                        \'card: \', NEW.card,
                        \'card_pay_id: \', NEW.card_pay_id,
                        \'bank_tax: \', NEW.bank_tax,
                        \'amount_paid: \', NEW.amount_paid,
                        \'balance_due: \', NEW.balance_due,
                        \'balance_to_give_back: \', NEW.balance_to_give_back,
                        \'balance_given: \', NEW.balance_given,
                        \'consider_for_next_payment: \', NEW.consider_for_next_payment,
                        \'bill_status: \', NEW.bill_status,
                        \'bill_delete_reason: \', NEW.bill_delete_reason,
                        \'bill_paid_date: \', NEW.bill_paid_date,
                        \'due_covered_bill_no: \', NEW.due_covered_bill_no,
                        \'due_covered_date: \', NEW.due_covered_date,
                        \'status: \', NEW.status,
                        \'billed_by: \', NEW.billed_by,
                        \'created_by: \', NEW.created_by,
                        \'updated_by: \', NEW.updated_by,
                        \'deleted_by: \', NEW.deleted_by
                    ),
                    NEW.updated_by,
                    NOW(),
                    NOW()
                );
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_patient_treatment_billing_delete
            AFTER DELETE ON patient_treatment_billings
            FOR EACH ROW
            BEGIN
                INSERT INTO patient_billing_audits (
                    billing_id, patient_id, billing_type, action, old_data, changed_by, created_at, updated_at
                )
                VALUES (
                    OLD.id,
                    OLD.patient_id,
                    "treatment",
                    \'DELETE\',
                    CONCAT_WS(\',\',
                        \'bill_id: \', OLD.bill_id,
                        \'appointment_id: \', OLD.appointment_id,
                        \'patient_id: \', OLD.patient_id,
                        \'treatment_total_amount: \', OLD.treatment_total_amount,
                        \'combo_offer_deduction: \', OLD.combo_offer_deduction,
                        \'previous_outstanding: \', OLD.previous_outstanding,
                        \'doctor_discount: \', OLD.doctor_discount,
                        \'insurance_paid: \', OLD.insurance_paid,
                        \'tax_percentile: \', OLD.tax_percentile,
                        \'tax: \', OLD.tax,
                        \'amount_to_be_paid: \', OLD.amount_to_be_paid,
                        \'gpay: \', OLD.gpay,
                        \'cash: \', OLD.cash,
                        \'card: \', OLD.card,
                        \'card_pay_id: \', OLD.card_pay_id,
                        \'bank_tax: \', OLD.bank_tax,
                        \'amount_paid: \', OLD.amount_paid,
                        \'balance_due: \', OLD.balance_due,
                        \'balance_to_give_back: \', OLD.balance_to_give_back,
                        \'balance_given: \', OLD.balance_given,
                        \'consider_for_next_payment: \', OLD.consider_for_next_payment,
                        \'bill_status: \', OLD.bill_status,
                        \'bill_delete_reason: \', OLD.bill_delete_reason,
                        \'bill_paid_date: \', OLD.bill_paid_date,
                        \'due_covered_bill_no: \', OLD.due_covered_bill_no,
                        \'due_covered_date: \', OLD.due_covered_date,
                        \'status: \', OLD.status,
                        \'billed_by: \', OLD.billed_by,
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_treatment_billing_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_treatment_billing_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_patient_treatment_billing_delete');

        // Drop tables
        Schema::dropIfExists('patient_treatment_billings');
    }
};
