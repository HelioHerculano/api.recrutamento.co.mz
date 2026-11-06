<?php

use App\Models\Job;
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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->foreignId('area_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->enum('status',[Job::ACTIVE,Job::INACTIVE])->default(Job::ACTIVE);
            
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
