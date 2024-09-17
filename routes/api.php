<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
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

Route::get('/get_all_company', [CompanyController::class,'get_all_company']);
Route::get('/create_company',[CompanyController::class,'create_company']);
Route::post('/add_company',[CompanyController::class,'add_company']);
Route::get('/edit_company/{id}',[CompanyController::class,'edit_company']);
Route::post('/update_company/{id}',[CompanyController::class,'update_company']);