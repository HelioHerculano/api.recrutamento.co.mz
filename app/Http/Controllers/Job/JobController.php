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

        $query = $query->with(['area']);

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
        ];

        $attributes = [
            'designation' => '"designação"',
            'area_id' => '"Area"'
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatorio'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }

        $newJob = Job::create($request->all());

        return $this->showOne($newJob);
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

    public function getJobByArea($area_id){
        $job = Job::where('area_id',$area_id)->get();

        return $this->showAll($job);
    }
}
