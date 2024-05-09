<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Job::when(request('name'), function($query){
            if(!empty(request('name'))) {
                $query->where('name', 'like', '%'.request('name').'%');
            }
        });

        $query = $query->when(request('area_id'), function ($query){
            if(!empty(request('area_id'))) {
                $query->where('area_id', '=', request('area_id'));
            }
        });

        $query = $query->when(request('job_id'), function ($query){
            if(!empty(request('job_id'))) {
                $query->where('id', '=', request('job_id'));
            }
        });

        $query = $query->when(request('start_date'), function ($query){
            if(!empty(request('start_date'))) {
                $query->where('start_date', '=', request('start_date'));
            }
        });

        $query = $query->when(request('status'), function ($query){
            if(!empty(request('status'))) {
                $query->where('status', '=', request('status'));
            }
        });

        $query = $query->with(['area']);

        $query = $query->orderBy('id','desc');

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
          'area_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ];

        $attributes = [
            'designation' => '"designação"',
            'area_id' => '"Area"',
            'start_date' => '"validade"',
            'end_date' => '"validade"',
            'description' => '"descrição"'
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatorio'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }

        $newJob = Job::create($request->all());

        return $this->showOne($newJob,"Vaga registada com sucesso");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = Job::find($id);

        return $this->showOne($job);
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
        public function update(Request $request, Job $job)
    {
       $roles = [
          'designation' => 'required',
          'area_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ];

        $attributes = [
            'designation' => '"designação"',
            'area_id' => '"Area"',
            'start_date' => '"validade"',
            'end_date' => '"validade"',
            'description' => '"descrição"'
        ];

          $costumMessages = [
              'required' => 'O campo é obrigatorio'
          ];

          $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

          if($validator->fails()){
              return $this->errorResponse($validator->errors(),422);
          }

        $job->fill($request->all());

        if($job->isClean()){
            return $this->errorResponse('Deve mundar os dados para poder actualizar',422);
        }

        $job->update();

        return $this->showOne($job,"Vaga actualizada com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();

        return $this->showOne($job,"Vaga removida com sucesso");
    }

    public function getJobByArea($area_id){
        $job = Job::where('area_id',$area_id)->where('status',1)->get();

        return $this->showAll($job);
    }

    // public function getJobById($id){
    //     $job = Job::find($id);

    //     return $this->showOne($job);
    // }

    public function getJson(){
        $job = Job::where('status',1)->with('area')->get();
        return $this->showAll($job);
    }
}
