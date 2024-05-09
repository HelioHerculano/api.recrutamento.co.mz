<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Candidate extends Model
{
    use HasFactory , HasApiTokens;

    const APPROVED_STATUS = [
        'pendente' => 'Pendente',
        'aprovado' => 'Aprovado',
        'reprovado' => 'Reprovado'
    ];

    const SINGLE = 1;
    const MARRIED = 2;
    const WIDOWER = 3;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'contact',
        'birth_date',
        'current_company',
        'current_position',
        'address',
        'nuit',
        'marital_status',
        'salary_expectation_min',
        'salary_expectation_max',
        'current_salary',
        'benefits',
        'country_id',
        'other_country_id',
        'province_id',
        'district_id',
        'employee_type_id',
        'gender',
        'remote_job',
        'for_travel',
        'have_children'
    ];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function other_country(){
        return $this->belongsTo(Country::class,"other_country_id");
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function employeeType(){
        return $this->belongsTo(EmployeeType::class);
    }

    public function experiences(){
        return $this->hasMany(Experience::class);
    }

    public function trainings(){
        return $this->hasMany(Training::class);
    }

    public function applications(){
        return $this->hasMany(Application::class);
    }

    public function candidateLanguage(){
        return $this->hasMany(Candidate_language::class);
    }

    public function attachments(){
        return $this->hasMany(Attachment::class);
    }

}
