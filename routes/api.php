<?php

use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\Area\AreaController;
use App\Http\Controllers\Attachment\AttachmentController;
use App\Http\Controllers\Candidate\CandidateController;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\District\DistrictController;
use App\Http\Controllers\DocumentType\DocumentTypeController;
use App\Http\Controllers\Experience\ExperienceController;
use App\Http\Controllers\Job\JobController;
use App\Http\Controllers\Language\LanguageController;
use App\Http\Controllers\Level\LevelController;
use App\Http\Controllers\Province\ProvinceController;
use App\Http\Controllers\Statistics\Statistics;
use App\Http\Controllers\Training\TrainingController;
use App\Http\Controllers\TrainingType\TrainingTypeController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(UserController::class)->group(function(){
    Route::post('login','login');
    Route::post('user','store');
    Route::get('user','index');
    Route::get('user/{id}','show');
    Route::put('user/{id}','update');
    Route::put('user/{id}/updatePassword','updatePassword');
    Route::delete('user/{id}','destroy');
    Route::put('user/{id}/active','active');
});

Route::post('/candidateAuth',[CandidateController::class,'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout',[UserController::class,'logout']);
    Route::post('/logout/candidate',[CandidateController::class,'logout']);
});
Route::get('candidateApplications', [CandidateController::class,'candidateApplications']);
Route::get('candidateApplications/{id}', [CandidateController::class,'candidateApplicationsById']);
Route::resource('language',LanguageController::class)->except(['create','edit']);
Route::resource('level',LevelController::class)->except(['create','edit']);
Route::get('jobByArea/{area_id}', [JobController::class,'getJobByArea']);
Route::get('job/json',[JobController::class,"getJson"]);
Route::resource('job',JobController::class)->except(['create','edit']);

Route::resource('candidate',CandidateController::class)->except(['create','edit']);
Route::get('candidate/verify/data',[CandidateController::class,'verifyData']);
Route::resource('experience',ExperienceController::class)->except(['create','edit']);
Route::resource('training',TrainingController::class)->except(['create','edit']);

Route::resource('application',ApplicationController::class)->except(['create','edit']);

Route::post('/profile/photo',[CandidateController::class,'postProfile']);
Route::post('attachment/temp',[AttachmentController::class,'upload']);
Route::resource('attachment',AttachmentController::class)->except(['create','edit']);


Route::resource('country',CountryController::class)->except(['create','edit']);

Route::get('province/{country_id}', [ProvinceController::class,'getByCountry']);
Route::get('district/{province_id}', [DistrictController::class,'getByProvince']);

Route::resource('trainingType',TrainingTypeController::class)->except(['create','edit']);

Route::resource('documentType',DocumentTypeController::class)->except(['create','edit']);

Route::resource('area',AreaController::class)->except(['create','edit']);

Route::get('statistics',[Statistics::class,'statistics']);

Route::get('/run-storage-link', function() {
    try {
        Artisan::call('storage:link');
        return 'Storage link created successfully.';
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
