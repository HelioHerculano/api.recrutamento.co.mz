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
            'candidate_id' => 'required',
            'file' => 'required|file'
        ];

        $attributes = [
            'designation' => '"designação"',
            'institution' => '"instituição"',
            'start_date' => '"data de inicio"',
            'end_date' => '"data do termino"',
            'training_type_id' => '"nivel"',
            'candidate_id' => '"candidato"',
            'file' => '"certificado"'
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatorio',
            'file' => 'O campo :attribute deve ser um ficheiro valido'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }

        // dd($request->only(['file']));

        if ($request->hasFile('file')) {
            $certificate_path = $request->file('file');
            $path = $this->saveAttachment($certificate_path,'attachments');
        }

        $data = $request->except("file");
        $data['certificate_path'] = $path;

        $newTraining = Training::create($data);

        return $this->showOne($newTraining,'Formação adicionada com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show(Training $training)
    {
        return $this->showOne($training);
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

        $training = Training::findOrFail($id);

        $roles = [
            'designation' => 'required',
            'institution' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'training_type_id' => 'required',
            'file' => 'nullable'
        ];

        $attributes = [
            'designation' => '"designação"',
            'institution' => '"instituição"',
            'start_date' => '"data de inicio"',
            'end_date' => '"data do termino"',
            'training_type_id' => '"nivel"',
            'file' => '"certificado"'
        ];

          $costumMessages = [
              'required' => 'O campo é obrigatorio'
          ];

          $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

          if($validator->fails()){
              return $this->errorResponse($validator->errors(),422);
          }

        $training->fill($request->all());

        // if($training->isClean()){
        //     return $this->errorResponse('Deve mundar os dados para poder actualizar',422);
        // }

        if ($request->hasFile('file')) {
            $certificate_path = $request->file('file');
            $path = $this->saveAttachment($certificate_path,'attachments');
            $data = $request->except("file");
            $data['certificate_path'] = $path;
        }else{
            $data = $request->except("file");
        }


        $training->update($data);

        return $this->showOne($training,"Formação actualizada com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Training $training)
    {
        $training->delete();
        return $this->showMessage("Formação removida com sucesso");
    }
}
