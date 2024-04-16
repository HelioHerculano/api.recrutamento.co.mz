<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

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
        'email',
        'contact',
        'birth_date',
        'current_company',
        'current_position',
        'address',
        'marital_status',
        'salary_expectation',
        'current_salary',
        'benefits',
        'country_id',
        'other_country_id',
        'province_id',
        'neighborhood_id',
        'employee_type_id'
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

    public function neighborhood(){
        return $this->belongsTo(Neighborhood::class);
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

}
