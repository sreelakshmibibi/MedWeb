<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('description')->nullable();
            $table->integer('duration')->nullable();
            $table->string('duration_type')->nullable();
            $table->enum('payment_status', ['Paid', 'Partially Paid', 'Not Paid'])
                ->default('Not Paid');
            $table->foreignId(column: 'employee_type_id')->constrained('employee_types');
            $table->char('status')->default('Y');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};