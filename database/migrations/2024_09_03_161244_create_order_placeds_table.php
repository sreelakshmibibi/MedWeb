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
        Schema::create('order_placeds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreignId('treatment_plan_id')->constrained('treatment_plans');
            $table->integer('teeth_id');
            $table->string('shade');
            $table->decimal('amount', 10, 3);
            $table->foreignId('technician_id')->constrained('technicians');
            $table->date('order_placed_on');
            $table->time('order_placed_time');
            $table->date('order_received_on');
            $table->time('order_received_time');
            $table->integer('order_status'); /*1=order placed 2=order received 3=Order cancelled*/
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_placeds');
    }
};
