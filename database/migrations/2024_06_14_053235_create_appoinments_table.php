<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appoinments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id')->unique(); 
            $table->unsignedBigInteger('patient_id');
            $table->foreignId('user')->constrained('users');
            $table->string('file_no', 20);
            $table->date('Date');
            $table->time('Time');
            $table->time('tim_to');
            $table->float('slot');
            $table->foreignId('doctor_id')->constrained('doctor_profiles');
            $table->string('type', 20);
            $table->string('status', 20);
            $table->string('app_note', 300);
            $table->string('nursing_note', 250);
            $table->string('docnurnote', 500);
            $table->string('yesno', 10);
            $table->string('doctor_note', 250);
            $table->string('dcheck', 5)->default('N');
            $table->string('dton', 250);
            $table->string('dref', 50);
            $table->string('nd', 5);
            $table->integer('nby');
            $table->integer('dby');
            $table->string('dtbill', 5);
            $table->foreignId('ap_stat')->constrained('appoinment_statuses');
            $table->date('recto');
            $table->date('next_app_dt');
            $table->foreignId('nextbr')->constrained('clinic_branches');
            $table->time('next_app_time');
            $table->dateTime('walk_in_tim');
            $table->dateTime('con_strt_time');
            $table->dateTime('con_end_time');
            $table->dateTime('biltime');
            $table->string('wby', 10)->default('R');
            $table->integer('prby');
            $table->foreignId('buk_by')->constrained('users');
            $table->foreignId('updt_by')->constrained('users');
            $table->string('wkoap', 20);
            $table->string('filyes', 5)->default('N');
            $table->string('imfil', 5)->default('N');
            $table->foreignId('br')->constrained('clinic_branches')->comment('Clinic Branch');
            $table->timestamps();

            $table->foreign('patient_id')
                  ->references('patient_id')
                  ->on('patient_profiles')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->index('app_id');
            $table->index('br');
            $table->index('patient_id');
            $table->index('walk_in_tim');
            $table->index('Date');
            $table->index('Time');
            $table->index('ap_stat');
            $table->index('doctor_id');
            $table->index('app_note');
            $table->index('nursing_note');
            $table->index('docnurnote');
            $table->index('doctor_note');
            $table->index('buk_by');
            $table->index('wkoap');
            $table->index('next_app_dt');
            $table->index('next_app_time');
            $table->index('user');
            $table->index('filyes');
            $table->index('yesno');
            $table->index('con_strt_time');
            $table->index('imfil');
            $table->index('con_end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appoinments', function (Blueprint $table) {
            $table->dropForeign(['user']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['ap_stat']);
            $table->dropForeign(['nextbr']);
            $table->dropForeign(['br']);
            $table->dropForeign(['buk_by']);
            $table->dropForeign(['updt_by']);
        });
        Schema::dropIfExists('appoinments');
    }
};
