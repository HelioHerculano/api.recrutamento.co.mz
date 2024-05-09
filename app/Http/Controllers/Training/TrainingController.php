<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Training::when(request('name'), function($query){
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

        $query = $query->with(['candidate','trainingType']);

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
            'designation' => 'required',
            'institution' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'training_type_id' => 'required',
            'candidate_id' => 'required'
        ];

        $attributes = [
            'designation' => '"designação"',
            'institution' => '"instituição"',
            'start_date' => '"data de inicio"',
            'end_date' => '"data do termino"',
            'training_type_id' => '"nivel"',
            'candidate_id' => '"candidato"'
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatorio'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }

        $newTraining = Training::create($request->all());

        return $this->showOne($newTraining);
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
