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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->string('institution');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('certificate_path');
            $table->string('training_type_id');
            $table->string('candidate_id');
            $table->timestamps();

            $table->foreign('training_type_id')->references('id')->on('training_types');

            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
