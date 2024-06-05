<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Attachment;
use App\Models\Candidate;
use App\Models\Candidate_job;
use App\Models\Candidate_language;
use App\Models\Experience;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
                    $query->where('name', 'like', '%'.request('name').'%')->orWhere('surname', 'like', '%'.request('name').'%');
                }
            });

            $query = $query->when(request('marital_status'), function ($query){
                if(!empty(request('marital_status'))) {
                    $query->where('marital_status','=',request('marital_status'));
                }
            });

            $query = $query->when(request('country_id'), function ($query){
                if(!empty(request('country_id'))) {
                    $query->where('country_id', '=', request('country_id'));
                }
            });

            $query = $query->when(request('province_id'), function ($query){
                if(!empty(request('province_id'))) {
                    $query->where('province_id', '=', request('province_id'));
                }
            });

            $query = $query->when(request('district_id'), function ($query){
                if(!empty(request('district_id'))) {
                    $query->where('district_id', '=', request('district_id'));
                }
            });

            if (!empty(request('training_level_id'))) {
                $query->whereHas('trainings', function ($subQuery) {
                    $subQuery->where('training_type_id', '=', request('training_level_id'));
                });
            }

            $query = $query->when(request('nuit'), function ($query){
                if(!empty(request('nuit'))) {
                    $query->where('nuit', '=', request('nuit'));
                }
            });

            $query = $query->when(request('isArchive'), function ($query){
                if(!empty(request('isArchive'))) {
                    $query->where('isArchive', '=', request('isArchive'));
                }
            });

            $query = $query->when(request('gender'), function ($query){
                if(!empty(request('gender'))) {
                    $query->where('gender', '=', request('gender'));
                }
            });

            $query = $query->when(request('birth_date'), function ($query){
                if(!empty(request('birth_date'))) {
                    $query->where('birth_date', '=', request('birth_date'));
                }
            });

            if (!empty(request('area_id'))) {
                $query->whereHas('applications.job', function ($subQuery) {
                    $subQuery->where('area_id', '=', request('area_id'));
                });
            }

            if (!empty(request('job_id'))) {
                $query->whereHas('applications.job', function ($subQuery) {
                    $subQuery->where('id', '=', request('job_id'));
                });
            }

            $query = $query->when(request('approved_status'), function ($query){
                if(!empty(request('approved_status'))) {
                    $query->where('approved_status', '=', request('approved_status'));
                }
            });

        $query = $query->with(['district.province.country','employeeType','experiences','trainings','applications.job']);

        $query = $query->orderBy('id','desc');

        return $query->paginate(10);
    }


    public function candidateApplications()
        {

            $query = Candidate::when(request('name'), function($query){
                if(!empty(request('name'))) {
                    $query->where('name', 'like', '%'.request('name').'%')->orWhere('surname', 'like', '%'.request('name').'%');
                }
            });

            $query = $query->when(request('email'), function ($query){
                if(!empty(request('email'))) {
                    $query->where('email', 'like', '%'.request('email').'%');
                }
            });

            $query = $query->when(request('contact'), function ($query){
                if(!empty(request('contact'))) {
                    $query->where('contact', '=', request('contact'));
                }
            });

            if (!empty(request('training_level_id'))) {
                $query->whereHas('trainings', function ($subQuery) {
                    $subQuery->where('training_type_id', '=', request('training_level_id'));
                });
            }

            $query = $query->when(request('nuit'), function ($query){
                if(!empty(request('nuit'))) {
                    $query->where('nuit', '=', request('nuit'));
                }
            });

            $query = $query->when(request('gender'), function ($query){
                if(!empty(request('gender'))) {
                    $query->where('gender', '=', request('gender'));
                }
            });

            $query = $query->when(request('birth_date'), function ($query){
                if(!empty(request('birth_date'))) {
                    $query->where('birth_date', '=', request('birth_date'));
                }
            });

            if (!empty(request('area_id'))) {
                $query->whereHas('applications.job', function ($subQuery) {
                    $subQuery->where('area_id', '=', request('area_id'));
                });
            }

            if (!empty(request('job_id'))) {
                $query->whereHas('applications.job', function ($subQuery) {
                    $subQuery->where('id', '=', request('job_id'));
                });
            }

            $query = $query->when(request('isArchive'), function ($query){
                if(!empty(request('isArchive'))) {
                    $query->where('isArchive', '=', request('isArchive'));
                }
            });

            $query = $query->when(request('approved_status'), function ($query){
                if(!empty(request('approved_status'))) {
                    $query->where('approved_status', '=', request('approved_status'));
                }
            });

            $query = $query->whereHas('applications');

            $query = $query->with(['district.province.country','employeeType','experiences','trainings','applications.job']);

            $query = $query->orderBy('id','desc');

            return $query->paginate(5);
        }

        public function candidateApplicationsById($id){
             $candidate = Candidate::where('id',$id)->with(['district.province.country','employeeType','experiences','trainings','applications.job.area','employeeType','trainings.trainingType','attachments.documentType','candidateLanguage.language','candidateLanguage.level'])->first();
             
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
                'candidateJobData',
                'candidateLanguageData'
            ]));

            $newTraining = null;
            $newExperience = null;
            $newLanguages = null;
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


                if(count($request->candidateExperienceData) > 0){
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

                if(!$request->isArquived){
                    if(count($request->candidateJobData) > 0){
                        // if($newExperience!=null){
                            foreach ($request->candidateJobData as $value) {
                            $data = [
                                'job_id' => $value['job_id'],
                                'candidate_id' => $newCandidate->id,
                            ];

                                $newJob = Application::create($data);
                            }
                            // return response->json()
                        // }
                    }
                }


                    foreach ($request->candidateLanguageData as $value) {
                        $data = [
                            'language_id' => $value['language_id'],
                            'level_id' => $value['level_id'],
                            'candidate_id' => $newCandidate->id,
                        ];

                            $newJob = Candidate_language::create($data);
                    }


                // if($newJob!=null){

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
                // }
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

    public function login(Request $request){

        $roles = [
            'nuit' => 'required',
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatório.'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages);

        if($validator->fails()){
            $data = [
                "errors" => $validator->errors(),
                "message" => "Preencha os campos"
            ];
            return $this->errorResponse($data,422);
        }

        $nuit = $request->nuit;

        // Encontre o funcionário com base no nuit
        $candidate = Candidate::where('nuit', $nuit)->first();

        if ($candidate) {
            // Se o funcionário for encontrado, gera um token
            $success['token'] = $candidate->createToken('CandidateAccessToken')->plainTextToken;
            $success['user'] = $candidate;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User login successfully'
            ];

            return response()->json($response,200);

        }else{
            $response = [
                'success' => false,
                'message' => 'Sem autorização, verrifica as tuas credencias!'
            ];
            return $this->errorResponse($response,401);
        }
    }

    public function verifyData(Request $request){

        if(!empty(request('email'))) {
            $verify = Candidate::where('email',$request->email)->first();
        }

        if(!empty(request('nuit'))) {
            $verify = Candidate::where('nuit',$request->nuit)->first();
        }

        if(!empty(request('contact'))) {
            $verify = Candidate::where('contact','+'.$request->contact)->first();
        }

        if($verify != null){
            return response()->json(["approved"=>false]);
        }else{
            return response()->json(["approved"=>true]);
        }
    }

    public function postProfile(Request $request){
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $path = $this->uploadProfile($attachment,'profiles');
        }

        return response()->json(["path"=>$path]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'success' => true,
            'message' => 'Logout success.'
        ],200);
    }
}
