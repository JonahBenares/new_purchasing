<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\LoginController;
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
Route::get('/login_form', [LoginController::class,'login_form']);
Route::post('/login_process', [LoginController::class,'login_process']);
Route::get('/change_password', [LoginController::class,'change_password']);
Route::get('/create_credential',[LoginController::class,'create_credential']);
Route::post('/edit_password',[LoginController::class,'edit_password']);
Route::get('/dashboard',[LoginController::class,'dashboard']);

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

Route::get('/get_all_company', [CompanyController::class,'get_all_company']);
Route::get('/create_company',[CompanyController::class,'create_company']);
Route::post('/add_company',[CompanyController::class,'add_company']);
Route::get('/edit_company/{id}',[CompanyController::class,'edit_company']);
Route::post('/update_company/{id}',[CompanyController::class,'update_company']);

Route::get('/get_all_vendors', [VendorController::class,'get_all_vendors']);
Route::get('/create_vendor',[VendorController::class,'create_vendor']);
Route::post('/add_vendor',[VendorController::class,'add_vendor']);
Route::get('/edit_vendor/{id}',[VendorController::class,'edit_vendor']);
Route::post('/update_vendor/{vendor_head_id}',[VendorController::class,'update_vendor']);
Route::get('/edit_branch/{id}',[VendorController::class,'edit_branch']);
Route::post('/update_branch/{vendor_details_id}',[VendorController::class,'update_branch']);
Route::get('/check_vendor_name/{vendor_name}', [VendorController::class,'check_vendor_name']);
Route::get('/no_terms_branch/{vendor_head_id}',[VendorController::class,'no_terms_branch']);
Route::post('/add_all_terms',[VendorController::class,'add_all_terms']);
