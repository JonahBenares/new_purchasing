<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AOQController;
use App\Http\Controllers\JOAOQController;
use App\Http\Controllers\PRController;
use App\Http\Controllers\JORController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/export-aoq/{aoq_head_id}', [AOQController::class, 'export_aoq']);
Route::get('/export-jo-aoq/{jo_aoq_head_id}', [JOAOQController::class, 'export_jo_aoq']);
Route::get('/export-prtemplate', [PRController::class, 'exportPRTemplate']);
Route::get('/export-jortemplate', [JORController::class, 'exportJORTemplate']);

Route::get('/{pathMatch}', function(){
    return view('welcome');
})->where('pathMatch',".*");