<?php

use App\Models\Candidate;
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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('contact')->unique();
            $table->string('birth_date');
            $table->enum("approved_status",Candidate::APPROVED_STATUS)->nullable();
            $table->string('current_company')->nullable();
            $table->string('current_position')->nullable();
            $table->string('address')->nullable();
            $table->enum("marital_status",[Candidate::SINGLE,Candidate::MARRIED,Candidate::WIDOWER])->nullable();
            $table->boolean("have_children")->default(0);
            $table->boolean("remote_job")->default(0);
            $table->boolean("for_travel")->default(0);
            $table->string("salary_expectation")->nullable();
            $table->string("current_salary")->nullable();
            $table->string("recruiter_assessment")->nullable(); //avaliação do recrutador
            $table->string("benefits")->nullable();
            $table->foreignId('country_id');
            $table->foreignId('other_country_id')->nullable();
            $table->foreignId('province_id');
            $table->foreignId('neighborhood_id')->nullable();
            $table->foreignId('employee_type_id');
            $table->timestamps();

            $table->foreign("country_id")->references("id")->on('countries');
            $table->foreign("other_country_id")->references("id")->on('countries');
            $table->foreign("province_id")->references("id")->on('provinces');
            $table->foreign("neighborhood_id")->references("id")->on('neighborhoods');
            $table->foreign("employee_type_id")->references("id")->on('employee_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
