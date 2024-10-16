<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RFQController;
use App\Http\Controllers\AOQController;
use App\Http\Controllers\PRController;
use App\Http\Controllers\JORController;
use App\Http\Controllers\DashboardController;
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

Route::get('/get_all_rfq', [RFQController::class,'get_all_rfq']);
Route::get('/pr_list', [RFQController::class,'all_pr']);
Route::get('/vendor_list', [RFQController::class,'all_vendor']);
Route::get('/get_pr_data/{pr_head_id}', [RFQController::class,'get_pr_data']);
Route::post('/add_rfq',[RFQController::class,'add_rfq']);
Route::get('/get_rfq_data/{rfq_head_id}', [RFQController::class,'get_rfq_data']);
Route::post('/add_additional_vendor',[RFQController::class,'add_additional_vendor']);
Route::get('/get_vendor_list/{rfq_head_id}', [RFQController::class,'vendor_list_data']);
Route::post('/add_additional_items',[RFQController::class,'add_additional_items']);
Route::get('/get_item_list/{rfq_head_id}', [RFQController::class,'item_list_data']);
Route::post('/canvass_complete/{rfq_vendor_id}', [RFQController::class,'canvass_complete']);
Route::post('/save_print_details',[RFQController::class,'save_print_details']);
Route::post('/canvass_complete_vendor',[RFQController::class,'canvass_complete_vendor']);
Route::post('/draft_vendor',[RFQController::class,'draft_vendor']);
Route::post('/add_additional_terms',[RFQController::class,'add_additional_terms']);
Route::post('/update_terms', [RFQController::class,'update_rfq_terms']);
Route::get('/remove_terms/{rfq_vendor_terms_id}', [RFQController::class,'remove_terms']);

Route::get('/get_all_aoq', [AOQController::class,'get_all_aoq']);
Route::get('/get_rfq_data/{rfq_head_id}', [AOQController::class,'get_rfq_data']);
Route::get('/get_aoq_data/{aoq_head_id}', [AOQController::class,'get_aoq_data']);

Route::post('/import_pr',[PRController::class,'import_pr']);
Route::post('/import_pr',[PRController::class,'import_pr']);
Route::get('/create_pr',[PRController::class,'create_pr']);
Route::get('/get_import_data/{id}',[PRController::class,'get_import_data']);
Route::post('/save_upload/{id}',[PRController::class,'save_upload']);
Route::post('/save_upload_draft/{id}',[PRController::class,'save_upload_draft']);
Route::post('/update_recomdate/{id}',[PRController::class,'update_recomdate']);
Route::get('/get_signatories',[PRController::class,'get_signatories']);
Route::get('/delete_item/{id}',[PRController::class,'delete_item']);
Route::get('/cancel_pr/{pr_head_id}',[PRController::class,'cancel_pr']);
Route::get('/processing_code',[PRController::class,'processing_code']);
Route::post('/save_manual',[PRController::class,'save_manual']);
Route::post('/save_manual_draft',[PRController::class,'save_manual_draft']);
Route::post('/generate_prno',[PRController::class,'generate_prno']);
Route::post('/insert_series',[PRController::class,'insert_series']);
Route::get('/get_allpr',[PRController::class,'get_allpr']);
Route::get('/get_view_details/{pr_head_id}',[PRController::class,'get_view_details']);
Route::post('/insert_referred/{id}',[PRController::class,'insert_referred']);
Route::post('/update_recomdate_view',[PRController::class,'update_recomdate_view']);
Route::post('/cancel_prdetails/{pr_details_id}',[PRController::class,'cancel_prdetails']);
Route::get('/cancel_allpr/{pr_head_id}',[PRController::class,'cancel_allpr']);

Route::post('/import_jor',[JORController::class,'import_jor']);
Route::get('/get_import_data_jor/{id}',[JORController::class,'get_import_data_jor']);
Route::post('/generate_jorno',[JORController::class,'generate_jorno']);
Route::get('/delete_jor_item/{id}',[JORController::class,'delete_jor_item']);
Route::get('/delete_jor_material_item/{id}',[JORController::class,'delete_jor_material_item']);
Route::get('/delete_jor_note_item/{id}',[JORController::class,'delete_jor_note_item']);
Route::post('/update_jor_labor_recomdate/{id}',[JORController::class,'update_jor_labor_recomdate']);
Route::post('/update_jor_labor_recomdate_view',[JORController::class,'update_jor_labor_recomdate_view']);
Route::post('/update_jor_material_recomdate/{id}',[JORController::class,'update_jor_material_recomdate']);
Route::post('/update_jor_material_recomdate_view',[JORController::class,'update_jor_material_recomdate_view']);
Route::post('/save_jor_upload/{id}',[JORController::class,'save_jor_upload']);
Route::post('/save_jor_upload_draft/{id}',[JORController::class,'save_jor_upload_draft']);
Route::get('/cancel_jor/{jor_head_id}',[JORController::class,'cancel_jor']);
Route::get('/create_jor',[JORController::class,'create_jor']);
Route::post('/save_jor_manual',[JORController::class,'save_jor_manual']);
Route::post('/save_jor_manual_draft',[JORController::class,'save_jor_manual_draft']);
Route::get('/get_alljor',[JORController::class,'get_alljor']);
Route::get('/get_jor_view_details/{jor_head_id}',[JORController::class,'get_jor_view_details']);
Route::get('/cancel_alljor/{jor_head_id}',[JORController::class,'cancel_alljor']);
Route::post('/cancel_jorlabordetails/{jor_labor_details_id}',[JORController::class,'cancel_jorlabordetails']);
Route::post('/cancel_jormaterialdetails/{jor_material_details_id}',[JORController::class,'cancel_jormaterialdetails']);
Route::get('/cancel_jornotes/{jor_notes_id}',[JORController::class,'cancel_jornotes']);

Route::get('/get_all_todo', [DashboardController::class,'get_all_todo']);
Route::get('/create_todo',[DashboardController::class,'create_todo']);
Route::post('/add_todo',[DashboardController::class,'add_todo']);
Route::get('/get_all_reminder', [DashboardController::class,'get_all_reminder']);
Route::get('/create_reminder',[DashboardController::class,'create_reminder']);
Route::post('/add_reminder',[DashboardController::class,'add_reminder']);
Route::get('/get_todo',[DashboardController::class,'get_todo']);
Route::get('/get_reminder',[DashboardController::class,'get_reminder']);
Route::get('/complete_todo/{id}',[DashboardController::class,'complete_todo']);
Route::get('/complete_reminder/{id}',[DashboardController::class,'complete_reminder']);
Route::post('/complete_all_todo',[DashboardController::class,'complete_all_todo']);
Route::post('/complete_all_reminder',[DashboardController::class,'complete_all_reminder']);
