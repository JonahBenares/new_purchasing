<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PRImport;
use Illuminate\Http\Request;
class PRController extends Controller
{
    public function import_pr(Request $request){
        if($request->file('upload_pr')){
            $filename=$request->file('upload_pr')->getClientOriginalName();
            $request->file('upload_pr')->storeAs('imports',$filename);
            Excel::import(new PRImport, request()->file('upload_pr'));
        }
    }
}
