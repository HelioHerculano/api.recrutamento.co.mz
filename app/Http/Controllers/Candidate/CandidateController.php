<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Attachment;
use App\Models\Candidate;
use App\Models\Candidate_job;
use App\Models\Experience;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $query = $query->with(['district.province.country','employeeType','experiences','trainings','applications.job']);

        return $query->paginate(10);
    }


    public function candidateApplications()
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

            $query = $query->whereHas('applications');

            $query = $query->with(['district.province.country','employeeType','experiences','trainings','applications.job']);

            return $query->paginate(10);
        }

        public function candidateApplicationsById($id){
             $candidate = Candidate::where('id',$id)->with(['district.province.country','employeeType','experiences','trainings','applications.job.area','employeeType','trainings','attachments.documentType'])->first();

             return $this->showOne($candidate);
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
        //   'current_company' => 'required',
        //   'current_position' => 'required',
          'address' => 'required',
          'marital_status' => 'required',
        //   'have_children' => 'required',
        //   'remote_job' => 'required',
        //   'for_travel' => 'required',
        //   'salary_expectation' => 'required',
        //   'current_salary' => 'required',
        //   'recruiter_assessment' => 'required',
          'nuit'=>'required',
        //   'benefits' => 'required',
          'country_id' => 'required',
        //   'other_country_id' => 'required',
          'province_id' => 'required',
          'district_id' => 'required',
        //   'employee_type_id' => 'required',
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
            'district_id' => 'bairros',
            'employee_type_id' => 'tipo proficional'
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatorio'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }

        DB::beginTransaction();
        // dump($request->all());
        // try {
            $newCandidate = Candidate::create($request->except([
                'candidateExperienceData',
                'candidateTrainingData',
                'candidateAttachmentData',
                'candidateJobData'
            ]));

            $newTraining = null;
            $newExperience = null;
            $newJob = null;
            $newAttach = null;

            if($newCandidate){
                foreach ($request->candidateTrainingData as $value) {
                    $data = [
                        'designation' => $value['training_area'],
                        'institution' => $value['school_name'],
                        'start_date' => $value['school_start_date'],
                        'end_date' => $value['school_end_date'],
                        'training_type_id' => $value['training_level'],
                        'candidate_id' => $newCandidate->id,
                        'certificate_path' => $value['path'],
                    ];

                    $newTraining = Training::create($data);
                }

            }


                if($newTraining!=null){
                    foreach ($request->candidateExperienceData as $value) {
                    // return response()->json($value,200);
                        $value['candidate_id'] = $newCandidate->id;
                        $newExperience = Experience::create($value);
                        // if($newExperience != null){
                        //     return $this->showOne($newCandidate);
                        // }
                    }
                    // return response()->json($newExperience,200);
                }

                if($newExperience!=null){
                    foreach ($request->candidateJobData as $value) {
                    $data = [
                        'job_id' => $value['job_id'],
                        'candidate_id' => $newCandidate->id,
                    ];

                        $newJob = Application::create($data);
                    }
                    // return response->json()
                }

                if($newJob!=null){

                    foreach ($request->candidateAttachmentData as $value) {
                    $data = [
                        'document_type_id' => $value['document_type_id'],
                        'candidate_id' => $newCandidate->id,
                        'path' => $value['path'],
                    ];

                        $newAttach = Attachment::create($data);

                    }
                    if($newAttach != null){
                        return $this->showOne($newCandidate);
                    }
                }
            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();

        //     return $this->errorResponse('Ocorreu um erro ao salvar os dados. Por favor, tente novamente.',500);
        // }
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
