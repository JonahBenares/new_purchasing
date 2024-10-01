<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PRImport;
use App\Imports\PRDetailsImport;
use Illuminate\Http\Request;
class PRController extends Controller
{
    public function import_pr(Request $request){
        if($request->file('upload_pr')){
            $filename=$request->file('upload_pr')->getClientOriginalName();
            $request->file('upload_pr')->storeAs('imports',$filename);
            $user_id = auth()->user()->id; 

            // $prImport = new PRImport;
            // $sheet = Excel::import(new PRImport($user_id), request()->file('upload_pr'));
            // $rows = $prImport->data;
            // print_r($sheet);
            Excel::import(new PRImport($user_id), request()->file('upload_pr'));
            Excel::import(new PRDetailsImport, request()->file('upload_pr'));
        }
    }

    public function create_pr(Request $request){
        $formData=[
            'purchase_request'=>'',
            'pr_no'=>'',
            'site_pr'=>'',
            'dae_prepared'=>'',
            'department'=>0,
            'urgency'=>'',
            'process_code'=>'',
            'enduse'=>'',
            'purpose'=>''
        ];
        return response()->json($formData);
    }

    public function manual_pr(Request $request){
        $validated=$this->validate($request,[
            'department_name'=>['unique:department','required','string']
        ]);
        Departments::create($validated);
    }
}
