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
        Schema::create('treatment_types', function (Blueprint $table) {
            $table->id();
            $table->string('treat_name'); //Treatment name
            $table->decimal('treat_cost', 10, 3); //Treatment cost
            $table->string('status', 5)->default('Y');  //Treatment status (treatment available or not)
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
           
            $table->timestamps();
            
            $table->softDeletes(); 
            //index
            $table->index('treat_name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_types');
    }
};
