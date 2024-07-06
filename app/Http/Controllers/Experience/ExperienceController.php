<?php

namespace App\Http\Controllers\Experience;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Experience::when(request('name'), function($query){
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

        $query = $query->with(['candidate']);

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
            'company' => 'required',
            'position' => 'required',
            'sector' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'responsibilities' => 'required',
            'candidate_id' => 'required'
        ];

        $attributes = [
            'company' => '"empresa"',
            'position' => '"cargo"',
            'sector' => '"sector"',
            'start_date' => '"data de inicio"',
            'end_date' => '"data do termino"',
            'responsibilities' => '"responsabilidades"',
            'candidate_id' => '"candidato"'
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatorio'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }

        $newExperience = Experience::create($request->all());

        return $this->showOne($newExperience,"Experiência adicionada com sucesso");
    }

    /**
     * Display the specified resource.
     */
    public function show(Experience $experience)
    {
        return $this->showOne($experience);
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
    public function update(Request $request, Experience $experience)
    {
        $roles = [
            'company' => 'required',
            'position' => 'required',
            'sector' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'responsibilities' => 'required'
        ];

        $attributes = [
            'company' => '"empresa"',
            'position' => '"cargo"',
            'sector' => '"sector"',
            'start_date' => '"data de inicio"',
            'end_date' => '"data do termino"',
            'responsibilities' => '"responsabilidades"'
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatorio'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }

        $experience->fill($request->all());

        if($experience->isClean()){
            return $this->errorResponse('Deve mundar os dados para poder actualizar',422);
        }

        $experience->update();

        return $this->showOne($experience,"Experiência actualizada com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Experience $experience)
    {
        $experience->delete();
        return $this->showMessage("Experiência removida com sucesso");
    }
}
