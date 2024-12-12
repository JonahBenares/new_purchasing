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
use App\Http\Controllers\POController;
use App\Http\Controllers\JOIController;
use App\Http\Controllers\JORFQController;
use App\Http\Controllers\JOAOQController;
use App\Http\Controllers\PODirectController;
use App\Http\Controllers\RepeatOrderPOController;
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
Route::get('/rfq_pr_list', [AOQController::class,'all_rfq_pr']);
Route::get('/rfq_list/{pr_no}', [AOQController::class,'all_rfq']);
Route::get('/create_new_aoq_details/{rfq_head_id}', [AOQController::class,'create_new_aoq']);
Route::post('/add_aoq_head',[AOQController::class,'add_aoq_head']);
Route::get('/aoq_head_details/{aoq_head_id}', [AOQController::class,'aoq_head_details']);
Route::get('/aoq_donete_details/{aoq_head_id}/{aoq_details_id}', [AOQController::class,'aoq_donete_details']);
Route::get('/cancel_aoq/{aoq_head_id}',[AOQController::class,'cancel_aoq']);
Route::post('/update_offers_awarded',[AOQController::class,'update_offers_awarded']);
Route::post('/update_offers_comments',[AOQController::class,'update_offers_comments']);
Route::post('/update_aoq_draft/{aoq_head_id}',[AOQController::class,'update_aoq_draft']);
Route::post('/save_aoq/{aoq_head_id}',[AOQController::class,'save_aoq']);
// Route::get('/export-aoq/{aoq_head_id}', [AOQController::class, 'export_aoq']);
Route::get('/vendor_offers/{rfq_vendor_id}/{rfq_head_id}',[AOQController::class,'vendor_offers']);
Route::post('/add_aoq_vendor',[AOQController::class,'add_aoq_vendor']);
Route::post('/done_te_aoq/{aoq_head_id}',[AOQController::class,'done_te_aoq']);
Route::post('/open_aoq/{aoq_head_id}',[AOQController::class,'open_aoq']);
Route::get('/aoq_status/{aoq_head_id}',[AOQController::class,'aoq_status']);

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
Route::get('/referred_cancelled_data/{prhead_id}/{prdetails_id}',[PRController::class,'referred_cancelled_data']);

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
Route::get('/cancelled_labor_data/{jorhead_id}/{jorlabordetails_id}',[JORController::class,'cancelled_labor_data']);
Route::get('/cancelled_material_data/{jorhead_id}/{jormaterialdetails_id}',[JORController::class,'cancelled_material_data']);

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

Route::get('/get_allpo',[POController::class,'get_allpo']);
Route::get('/supplier_dropdown', [POController::class,'supplier_dropdown']);
Route::get('/get_prno/{vendor_details_id}', [POController::class,'get_prno']);
Route::get('/generate_po/{vendor_details_id}/{pr_no}', [POController::class,'generate_po']);
Route::get('/check_balance/{pr_details_id}', [POController::class,'check_balance']);
Route::post('/save_po', [POController::class,'save_po']);
Route::get('/po_viewdetails/{po_head_id}', [POController::class,'po_viewdetails']);
Route::post('/insert_internalcomment/{id}',[POController::class,'insert_internalcomment']);
Route::post('/cancel_po_items/{po_details_id}',[POController::class,'cancel_po_items']);
Route::post('/cancel_all_po/{po_head_id}',[POController::class,'cancel_all_po']);
Route::get('/delete_terms/{id}',[POController::class,'delete_terms']);
Route::get('/delete_instructions/{id}',[POController::class,'delete_instructions']);
Route::get('/check_balance_rev/{po_head_id}/{pr_details_id}', [POController::class,'check_balance_rev']);
Route::post('/save_change_po', [POController::class,'save_change_po']);
Route::post('/save_approved_revision', [POController::class,'save_approved_revision']);
Route::get('/old_revision_data/{id}',[POController::class,'old_revision_data']);
Route::get('/view_revision_data/{id}',[POController::class,'view_revision_data']);
Route::get('/po_dropdown', [POController::class,'po_dropdown']);
Route::get('/generate_dr/{po_head_id}', [POController::class,'generate_dr']);
Route::get('/get_offer/{rfq_offer_id}', [POController::class,'get_offer']);
Route::get('/check_dr_balance/{po_dr_id}/{po_details_id}', [POController::class,'check_dr_balance']);
Route::get('/check_remaining_dr_balance/{po_details_id}', [POController::class,'check_remaining_dr_balance']);
Route::post('/save_dr', [POController::class,'save_dr']);
Route::get('/get_dr_view/{po_dr_id}', [POController::class,'get_dr_view']);
Route::get('/get_alldr', [POController::class,'get_alldr']);

Route::get('/get_direct_pr', [PODirectController::class,'get_direct_pr']);
Route::get('/direct_supplier_dropdown', [PODirectController::class,'direct_supplier_dropdown']);
Route::get('/generate_dpo/{pr_no}/{vendor_details_id}', [PODirectController::class,'generate_dpo']);
Route::post('/save_direct_po', [PODirectController::class,'save_direct_po']);
Route::get('/dpo_viewdetails/{po_head_id}', [PODirectController::class,'dpo_viewdetails']);
Route::get('/delete_dpo_terms/{id}',[PODirectController::class,'delete_dpo_terms']);
Route::get('/delete_dpo_instructions/{id}',[PODirectController::class,'delete_dpo_instructions']);

Route::get('/get_repeat_pr', [RepeatOrderPOController::class,'get_repeat_pr']);
Route::get('/repeat_supplier_dropdown', [RepeatOrderPOController::class,'repeat_supplier_dropdown']);
Route::get('/generate_rpo/{pr_no}/{vendor_details_id}', [RepeatOrderPOController::class,'generate_rpo']);
Route::get('/get_po_items/{item_desc}/{vendor_details_id}', [RepeatOrderPOController::class,'get_po_items']);
Route::post('/save_repeat_po', [RepeatOrderPOController::class,'save_repeat_po']);
Route::get('/rpo_viewdetails/{po_head_id}', [RepeatOrderPOController::class,'rpo_viewdetails']);
Route::get('/delete_rpo_terms/{id}',[RepeatOrderPOController::class,'delete_rpo_terms']);
Route::get('/delete_rpo_instructions/{id}',[RepeatOrderPOController::class,'delete_rpo_instructions']);
Route::post('/save_approved_repeat_revision', [RepeatOrderPOController::class,'save_approved_repeat_revision']);
Route::post('/save_change_rpo', [RepeatOrderPOController::class,'save_change_rpo']);

Route::get('/get_all_jo_rfq', [JORFQController::class,'get_all_jo_rfq']);
Route::get('/jor_list', [JORFQController::class,'all_jor']);
Route::get('/vendor_list', [JORFQController::class,'all_vendor']);
Route::get('/get_jor_data/{jor_head_id}', [JORFQController::class,'get_jor_data']);
Route::post('/add_jo_rfq',[JORFQController::class,'add_jo_rfq']);
Route::get('/get_jo_rfq_data/{jo_rfq_head_id}', [JORFQController::class,'get_jo_rfq_data']);
Route::post('/save_jo_rfq_print_details',[JORFQController::class,'save_print_jo_rfq_details']);
Route::get('/get_jo_rfq_vendor_list/{jo_rfq_head_id}', [JORFQController::class,'jo_rfq_vendor_list_data']);
Route::post('/add_additional_jo_rfq_vendor',[JORFQController::class,'add_additional_jo_rfq_vendor']);
Route::get('/get_jo_rfq_item_list/{jo_rfq_head_id}', [JORFQController::class,'get_jo_rfq_item_list']);
Route::post('/add_additional_labor_material',[JORFQController::class,'add_additional_labor_material']);
// Route::post('/canvass_complete/{rfq_vendor_id}', [JORFQController::class,'canvass_complete']);
Route::post('/canvass_complete_jo_vendor',[JORFQController::class,'canvass_complete_jo_vendor']);
Route::post('/draft_jo_vendor',[JORFQController::class,'draft_jo_vendor']);
Route::post('/add_additional_jo_terms',[JORFQController::class,'add_additional_jo_terms']);
Route::post('/update_jo_terms', [JORFQController::class,'update_jo_terms']);
Route::get('/remove_jo_terms/{jo_rfq_terms_id}', [JORFQController::class,'remove_jo_terms']);

Route::get('/get_all_jo_aoq', [JOAOQController::class,'get_all_jo_aoq']);
Route::get('/jo_rfq_jor_list', [JOAOQController::class,'all_jo_rfq_jor']);
Route::get('/jo_rfq_list/{jor_no}', [JOAOQController::class,'all_jo_rfq']);
Route::get('/create_new_jo_aoq_details/{jo_rfq_head_id}', [JOAOQController::class,'create_new_jo_aoq']);
Route::post('/add_jo_aoq_head',[JOAOQController::class,'add_jo_aoq_head']);
Route::get('/jo_aoq_head_details/{jo_aoq_head_id}', [JOAOQController::class,'jo_aoq_head_details']);
Route::get('/joaoq_donete_details/{jo_aoq_head_id}/{jo_aoq_details_id}', [JOAOQController::class,'joaoq_donete_details']);
Route::get('/cancel_jo_aoq/{jo_aoq_head_id}',[JOAOQController::class,'cancel_jo_aoq']);
Route::post('/update_labor_offers_awarded',[JOAOQController::class,'update_labor_offers_awarded']);
Route::post('/update_labor_offers_comments',[JOAOQController::class,'update_labor_offers_comments']);
Route::post('/update_material_offers_awarded',[JOAOQController::class,'update_material_offers_awarded']);
Route::post('/update_material_offers_comments',[JOAOQController::class,'update_material_offers_comments']);
Route::post('/update_jo_aoq_draft/{jo_aoq_head_id}',[JOAOQController::class,'update_jo_aoq_draft']);
Route::post('/save_jo_aoq/{jo_aoq_head_id}',[JOAOQController::class,'save_jo_aoq']);
// // Route::get('/export-aoq/{aoq_head_id}', [JOAOQController::class, 'export_aoq']);
Route::get('/jo_vendor_offers/{jo_rfq_vendor_id}/{jo_rfq_head_id}',[JOAOQController::class,'jo_vendor_offers']);
Route::post('/add_jo_aoq_vendor',[JOAOQController::class,'add_jo_aoq_vendor']);
Route::post('/done_te_jo_aoq/{jo_aoq_head_id}',[JOAOQController::class,'done_te_jo_aoq']);
Route::post('/open_jo_aoq/{jo_aoq_head_id}',[JOAOQController::class,'open_jo_aoq']);
Route::get('/joaoq_status/{jo_aoq_head_id}',[JOAOQController::class,'joaoq_status']);

Route::get('/get_alljoi',[JOIController::class,'get_alljoi']);
Route::get('/jo_supplier_dropdown', [JOIController::class,'jo_supplier_dropdown']);
Route::get('/get_jorno/{vendor_details_id}', [JOIController::class,'get_jorno']);
Route::get('/generate_joi/{vendor_details_id}/{jor_no}', [JOIController::class,'generate_joi']);
Route::get('/check_labor_balance/{jor_labor_details_id}/{jo_rfq_labor_offer_id}', [JOIController::class,'check_labor_balance']);
Route::get('/check_material_balance/{jo_material_details_id}/{jo_rfq_material_offer_id}', [JOIController::class,'check_material_balance']);
Route::get('/delete_jo_terms/{id}',[JOIController::class,'delete_jo_terms']);
Route::get('/delete_jo_instructions/{id}',[JOIController::class,'delete_jo_instructions']);
Route::post('/save_joi', [JOIController::class,'save_joi']);
Route::get('/jo_viewdetails/{joi_head_id}', [JOIController::class,'jo_viewdetails']);
Route::get('/delete_jo_terms/{id}',[JOIController::class,'delete_jo_terms']);
Route::get('/delete_jo_instructions/{id}',[JOIController::class,'delete_jo_instructions']);
Route::post('/cancel_all_jo/{joi_head_id}',[JOIController::class,'cancel_all_jo']);
Route::post('/cancel_jo_items/{joi_labor_details_id}',[JOIController::class,'cancel_jo_items']);
Route::post('/cancel_jo_material_items/{joi_material_details_id}',[JOIController::class,'cancel_jo_material_items']);
Route::post('/save_change_joi', [JOIController::class,'save_change_joi']);
Route::post('/save_joi_approved_revision', [JOIController::class,'save_joi_approved_revision']);
Route::get('/old_jo_revision_data/{id}',[JOIController::class,'old_jo_revision_data']);
Route::get('/view_jo_revision_data/{id}',[JOIController::class,'view_jo_revision_data']);
Route::get('/joi_dropdown', [JOIController::class,'joi_dropdown']);
Route::get('/generate_jo_dr/{joi_head_id}', [JOIController::class,'generate_jo_dr']);
Route::get('/get_offer_labor/{jo_rfq_labor_offer_id}', [JOIController::class,'get_offer_labor']);
Route::get('/get_offer_material/{jo_rfq_material_offer_id}', [JOIController::class,'get_offer_material']);

Route::get('/check_jo_labor_dr_balance/{joi_dr_id}/{joi_labor_details_id}', [JOIController::class,'check_jo_labor_dr_balance']);
Route::get('/check_jo_material_dr_balance/{joi_dr_id}/{joi_material_details_id}', [JOIController::class,'check_jo_material_dr_balance']);
Route::get('/check_remaining_dr_labor_balance/{joi_labor_details_id}', [JOIController::class,'check_remaining_dr_labor_balance']);
Route::get('/check_remaining_dr_material_balance/{joi_labor_details_id}', [JOIController::class,'check_remaining_dr_material_balance']);
Route::post('/save_jo_dr', [JOIController::class,'save_jo_dr']);
Route::get('/get_jo_alldr', [JOIController::class,'get_jo_alldr']);
Route::get('/get_jo_dr_view/{joi_dr_id}', [JOIController::class,'get_jo_dr_view']);
