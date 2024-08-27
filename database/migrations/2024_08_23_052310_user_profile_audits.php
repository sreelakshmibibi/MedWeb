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
        Schema::create('user_profile_audits', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('table_name');/*patient_profile, insurance, history*/
            $table->enum('action', ['INSERT', 'UPDATE', 'DELETE']);
            $table->text('old_data')->nullable();
            $table->text('new_data')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamps();

            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile_audits');
    }

};
