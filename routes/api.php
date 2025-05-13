<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\TalonariosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DatesBdController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', [AuthController::class,'login']);
Route::post('sendCode', [AuthController::class,'sendCode']);
Route::post('loginRed', [AuthController::class,'loginRed']); 

Route::post('register', [AuthController::class,'registerUser']); 
Route::middleware('auth:api')->post('registerWithRedes', [AuthController::class,'registerUserRedes']); 

Route::post('validatePhone', [AuthController::class,'validatePhone']); 
Route::post('confirmPhone', [AuthController::class,'confirmPhone']); 


Route::post('chat', [ChatController::class,'createChat']); 
Route::get('getChat/{id}', [ChatController::class,'allChats']); 
Route::get('consultChat/{phone}', [ChatController::class,'consultChat']); 
Route::post('postChat', [ChatController::class,'postChat']); 

Route::get('getCountry', [DatesBdController::class, 'getCountry']);

Route::get('allTalonarios', [TalonariosController::class, 'allTalonarios']);
Route::get('talonario-by-id/{id}', [TalonariosController::class, 'talonario_by_id']);
Route::post('verifyNumbers', [TalonariosController::class, 'verifyNumbers']);
Route::post('takeNumber', [TalonariosController::class, 'takeNumber']);
Route::get('allTalonariosWinner', [TalonariosController::class, 'allTalonariosWinner']);
Route::post('consultNumbers', [TalonariosController::class, 'numbers_by_client']);


Route::middleware('auth:api')->group(function () {
   
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
});   