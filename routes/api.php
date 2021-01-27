<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController; 
use App\Http\Controllers\AreaController; 
use App\Http\Controllers\BilletController; 
use App\Http\Controllers\CityController; 
use App\Http\Controllers\CompanyController; 
use App\Http\Controllers\DocController; 
use App\Http\Controllers\GoodController; 
use App\Http\Controllers\LostFoundController; 
use App\Http\Controllers\PetController; 
use App\Http\Controllers\StateController; 
use App\Http\Controllers\UnitController; 
use App\Http\Controllers\WallController; 
use App\Http\Controllers\WarningController; 


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

Route::get('/ping', function () {
    return ['pong' => true]; 
}); 

Route::get('/401',[AuthController::class,'unauthorized'])->name('login'); 

Route::post('/auth/login',[AuthController::class,'login']); 
Route::post('/auth/register',[AuthController::class,'register']); 

Route::middleware('auth:api')->group(function(){
    Route::post('/auth/validate',[AuthController::class,'validateToken']);
    Route::get('/auth/logout',[AuthController::class,'logout']);

    Route::get('/walls',[WallController::class,'getAll']);
    Route::post('/wall/{id}/like',[AuthController::class,'like']);

    Route::get('/docs',[DocController::class,'getAll']); 

    Route::get('/warning',[WarningController::class,'getMyWarnings']); 
    Route::post('/warning',[WarningController::class,'insert']); 
    Route::post('/warning/file',[WarningController::class,'addWarningFile']);

    Route::get('/billets',[BilletController::class,'getAll']);

    Route::get('/lostfound',[LostFoundController::class,'getAll']);
    Route::post('/lostfound',[LostFoundController::class,'insert']); 
    Route::put('lostfound/{id}',[LostFoundController::class,'update']); 

    Route::get('/unit/{id]',[UnitController::class,'getInfo']);
    Route::post('/unit/{id}/addperson',[UnitController::class,'addPerson']);
    Route::post('/unit/{id}/addvehicle',[UnitController::class,'addVehicle']);
    Route::post('/unit/{id}/addpet',[UnitController::class,'addPet']);

    Route::post('/unit/{id}/removeperson',[UnitController::class,'removePerson']);
    Route::post('/unit/{id}/removevehicle',[UnitController::class,'removeVehicle']);
    Route::post('/unit/{id}/removepet',[UnitController::class,'removePet']);

    Route::get('/reservations/',[ReservationController::class,'getReservation']);
    Route::post('/reservation/{id}',[ReservationController::class,'setReservation']);

    Route::get('/reservation/{id}/disabledates',[ReservationController::class,'getDisableDates']);
    Route::get('/reservation/{id}/times',[ReserevationController::class,'getTimes']);

    Route::get('/myreservations',[ReservationController::class,'getMyReservations']);
    Route::delete('/myreservation/{id}',[ReservationController::class,'delMyReservation']); 
});