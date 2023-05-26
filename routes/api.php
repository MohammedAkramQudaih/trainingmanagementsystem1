<?php

use App\Http\Controllers\Api\TraineeAttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource(
    'trainees',
    \App\Http\Controllers\Api\TraineesController::class
);
Route::apiResource(
    'disciplines',
    \App\Http\Controllers\Api\DisciplinesController::class
);
Route::apiResource(
    'advisors',
    \App\Http\Controllers\Api\AdvisorsController::class
)->middleware('auth:sanctum');
Route::apiResource(
    'managers',
    \App\Http\Controllers\Api\ManagersController::class,

)->middleware('auth:sanctum');

Route::apiResource(
    'programs',
    \App\Http\Controllers\Api\ProgramsController::class
)->middleware('auth:sanctum');

Route::apiResource(
    'files',
    \App\Http\Controllers\Api\StoredFileController::class
);

Route::apiResource(
    'notifications',
    \App\Http\Controllers\Api\NotificationsController::class
)->middleware('auth:sanctum');;

Route::post('/manager/accept-trainee/{id}',
    [\App\Http\Controllers\Api\ManagersController::class, 'acceptTrainee'])->middleware('auth:sanctum');
Route::post('/manager/trainees/{trainee_id}/programs/{program_id}',
    [\App\Http\Controllers\Api\ManagersController::class, 'acceptTrainingRequest'])->middleware('auth:sanctum');
Route::post('/trainees/attendance', [TraineeAttendanceController::class, 'store'])->middleware('auth:sanctum');

Route::post('/trainees/create-meeting',[\App\Http\Controllers\Api\MeetingsController::class,'store'])->middleware('auth:sanctum');

Route::post('/trainee/apply-program/',
    [\App\Http\Controllers\Api\TrainingRequestsController::class, 'store'])->middleware('auth:sanctum');

Route::post('/advisor/acceptMeeting',[\App\Http\Controllers\Api\AdvisorsController::class,'acceptMeeting'])->middleware('auth:sanctum');;
Route::get('/advisor/get-meetings/{advisor_id}',[\App\Http\Controllers\Api\AdvisorsController::class,'getMeetingsRequests'])
    ->middleware('auth:sanctum');
Route::get('/advisor/get-programs',[\App\Http\Controllers\Api\AdvisorsController::class,'getAllPrograms'])
    ->middleware('auth:sanctum');
Route::get('/advisor/get-trainees/{program_id}',[\App\Http\Controllers\Api\AdvisorsController::class,'getAllTraineesByProgram'])
    ->middleware('auth:sanctum');;


Route::get('/advisor/profile/',
    [\App\Http\Controllers\Api\AdvisorsController::class, 'getAdvisorInfo'])->middleware('auth:sanctum');
Route::get('/manager/profile/',
    [\App\Http\Controllers\Api\ManagersController::class, 'getManagerInfo'])->middleware('auth:sanctum');
Route::get('/trainee/profile/',
    [\App\Http\Controllers\Api\TraineesController::class, 'getTraineeInfo'])->middleware('auth:sanctum');




Route::post('/login', [AuthController::class, 'login']);
Route::post('/trainee/login', [AuthController::class, 'traineeLogin']);
Route::post('/advisor/login', [AuthController::class, 'advisorLogin']);
Route::post('/manager/login', [AuthController::class, 'managerLogin']);

Route::post('/advisor/register', [AuthController::class, 'registerAdvisor']);
Route::post('/manager/register', [AuthController::class, 'registerManager']);

Route::post('/logout/{id}', [AuthController::class, 'logout']);


