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
        Schema::create('candidate_languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id');
            $table->foreignId('language_id');
            $table->foreignId('level_id');
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('level_id')->references('id')->on('levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_languages');
    }
};
