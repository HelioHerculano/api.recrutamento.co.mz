<?php

use App\Http\Controllers\Candidate\CandidateController;
use App\Http\Controllers\Experience\ExperienceController;
use App\Http\Controllers\Job\JobController;
use App\Http\Controllers\Training\TrainingController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::resource('job',JobController::class)->except(['create','edit']);
Route::resource('candidate',CandidateController::class)->except(['create','edit']);
Route::resource('experience',ExperienceController::class)->except(['create','edit']);
Route::resource('training',TrainingController::class)->except(['create','edit']);

// Route::get('/',function(){
//     return "ksdjdsj";
// });
