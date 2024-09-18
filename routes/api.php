<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
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
Route::get('/get_all_department', [DepartmentController::class,'get_all_department']);
Route::get('/create_department',[DepartmentController::class,'create_department']);
Route::post('/add_department',[DepartmentController::class,'add_department']);
Route::get('/edit_department/{id}',[DepartmentController::class,'edit_department']);
Route::post('/update_department/{id}',[DepartmentController::class,'update_department']);

Route::get('/get_all_employee', [EmployeeController::class,'get_all_employee']);
Route::get('/create_employee',[EmployeeController::class,'create_employee']);
Route::post('/add_employee',[EmployeeController::class,'add_employee']);
Route::get('/edit_employee/{id}',[EmployeeController::class,'edit_employee']);
Route::post('/update_employee/{id}',[EmployeeController::class,'update_employee']);
Route::get('/get_department',[EmployeeController::class,'get_department']);