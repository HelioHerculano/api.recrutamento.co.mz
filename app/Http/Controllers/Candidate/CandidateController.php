<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CandidateController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Candidate::when(request('name'), function($query){
            if(!empty(request('name'))) {
                $query->where('name', 'like', '%'.request('name').'%');
            }
        });

        $query = $query->when(request('nuit'), function ($query){
            if(!empty(request('nuit'))) {
                $query->where('nuit', '=', request('nuit'));
            }
        });

        $query = $query->when(request('origin_id'), function ($query){
            if(!empty(request('origin_id'))) {
                $query->where('origin_id', '=', request('origin_id'));
            }
        });

        $query = $query->when(request('status'), function ($query){
            if(!empty(request('status'))) {
                $query->where('status', '=', request('status'));
            }
        });

        $query = $query->with(['neighborhood.province.country','employeeType','experiences','trainings']);

        return $query->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $roles = [
          'name' => 'required',
          'email' => 'required',
          'contact' => 'required',
          'birth_date' => 'required',
        //   'approved_status' => 'required',
          'current_company' => 'required',
          'current_position' => 'required',
          'address' => 'required',
          'marital_status' => 'required',
        //   'have_children' => 'required',
        //   'remote_job' => 'required',
        //   'for_travel' => 'required',
          'salary_expectation' => 'required',
          'current_salary' => 'required',
        //   'recruiter_assessment' => 'required',
          'benefits' => 'required',
          'country_id' => 'required',
          'other_country_id' => 'required',
          'province_id' => 'required',
          'neighborhoods_id' => 'required',
          'employee_type_id' => 'required',
        ];

        $attributes = [
            'name'=>'nome',
            'contact'=>'contacto',
            'birth_date'=>'data de nascimento',
            'current_company'=>'empresa actual',
            'current_position'=>'posição actual',
            'address'=>'endereço',
            'marital_status'=>'estado civil',
            'salary_expectation'=>'expectativa salarial',
            'current_salary'=> 'salario actual',
            'recruiter_assessment'=>'avaliação do recrutador',
            'benefits' => 'benificios',
            'country_id' => 'pais',
            'other_country_id' => 'outro pais',
            'province_id' => 'provincia',
            'neighborhoods_id' => 'bairros',
            'employee_type_id' => 'tipo proficional'
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatorio'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }

        $newCandidate = Candidate::create($request->all());

        return $this->showOne($newCandidate);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
