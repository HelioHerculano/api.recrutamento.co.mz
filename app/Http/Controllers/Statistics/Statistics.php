<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Http\Request;

class Statistics extends Controller
{
    public function statistics(){
        $jobs = Job::all()->count();
        $candidates = Candidate::all()->count();
        $candidatesArchived = Candidate::where('isArchive',1)->count();
        $candidatesNotArchived = Candidate::where('isArchive',2)->count();

        return response()->json(["jobs"=>$jobs,"candidates"=>$candidates,"candidatesArchived"=>$candidatesArchived,"candidatesNotArchived"=>$candidatesNotArchived]);
    }
}
