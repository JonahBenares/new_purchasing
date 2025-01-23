<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use App\Imports\JORImport;
use App\Imports\JORLaborImport;
use App\Imports\JORMaterialImport;
use App\Models\JORHead;
use App\Models\JORLaborDetails;
use App\Models\JOILaborDetails;
use App\Models\JOIMaterialDetails;
use App\Models\JORMaterialDetails;
use App\Models\JORSeries;
use App\Models\JORNotes;
use App\Models\Departments;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;
use Config;
class JORController extends Controller
{
    public function import_jor(Request $request){
        
            if($request->file('upload_jor')){
                $filename=$request->file('upload_jor')->getClientOriginalName();
                $request->file('upload_jor')->storeAs('imports',$filename);
                $user_id = auth()->user()->id;
                try {
                    DB::beginTransaction();
                    $jorImport = new JORImport($user_id);
                    $sheet1 = Excel::import($jorImport, request()->file('upload_jor'));
                    $head = $jorImport->data;
                    $jor_head_id = $jorImport->id;
                    $jorlaborImportdetails=array();
                    if($jorImport->id!=null){
                        $jorlaborImportdetails = new JORLaborImport($jor_head_id);
                        $sheet2 = Excel::import($jorlaborImportdetails, request()->file('upload_jor'));
                        $labor_details = $jorlaborImportdetails->data;
                    }
                    DB::commit();
                    return response()->json([
                        'jor_head_id'=>$jor_head_id,
                        'jorhead'=>$head,
                    ],200);
                } catch (Throwable $e) {
                    DB::rollBack();
                    echo 'error';
                }
            } 
    }

    public function get_import_data_jor($id){
        $jor_head = JORHead::where('id',$id)->where('status','!=','Cancelled')->first();
        $jor_labor_details = JORLaborDetails::where('jor_head_id',$id)->where('status','!=','Cancelled')->get();
        $jor_material_details = JORMaterialDetails::where('jor_head_id',$id)->where('status','!=','Cancelled')->get();
        $jor_notes_details = JORNotes::where('jor_head_id',$id)->where('status','!=','Cancelled')->get();
        return response()->json([
            'jor_head'=>$jor_head,
            'jor_labor_details'=>$jor_labor_details,
            'jor_material_details'=>$jor_material_details,
            'jor_notes_details'=>$jor_notes_details,
        ],200);
    }

    public function generate_jorno(Request $request){
        $year= ($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? date("Y", strtotime($request->date_prepared)) : date('Y');
        $year_short = ($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? date("y", strtotime($request->date_prepared)) : date('y');
        $department_code=Departments::where('id',$request->department)->value('department_code');
        $series_rows = JORSeries::where('year',$year)->count();
        $company=Config::get('constants.company');
        if($series_rows==0){
            $max_series='1';
            $jor_series='0001';
            $jor_no = 'JOR'.$department_code.$year_short."-".$jor_series;
        } else {
            $max_series=JORSeries::where('year',$year)->max('series');
            $jor_series=$max_series+1;
            $exp=explode('-',$request->jor_no);
            if($request->props_id==0){
                if($request->jor_no!='' && $request->jor_no!='undefined'){
                    $jor_no = 'JOR'.$department_code.$year_short."-".Str::padLeft($exp[1], 4,'000');
                }else{
                    $jor_no = 'JOR'.$department_code.$year_short."-".Str::padLeft($jor_series, 4,'000');
                }
            }else{
                $jor_no = 'JOR'.$department_code.$year_short."-".Str::padLeft($exp[1], 4,'000');
            }
        }
        return $jor_no."-".$company;
    }

    public function delete_jor_item($id){
        $deleted = JORLaborDetails::find($id);
        $deleted->delete();
    }

    public function delete_jor_material_item($id){
        $deleted = JORMaterialDetails::find($id);
        $deleted->delete();
    }

    public function delete_jor_note_item($id){
        $deleted = JORNotes::find($id);
        $deleted->delete();
    }

    public function update_jor_labor_recomdate(Request $request, $id){
        $recom_date=$request->input("recom_date");
        if(count(json_decode($recom_date))>0){
            foreach(json_decode($recom_date) AS $c){
                $update_recomdate=JORLaborDetails::where('id',$id)->where('status','!=','Cancelled')->update([
                    'recom_date'=>$c,
                    'recom_status'=>'Open',
                ]);
            }
        }
    }

    public function update_jor_labor_recomdate_view(Request $request){
        $recom_date=$request->input("recom_date");
        if(count(json_decode($recom_date))>0){
            foreach(json_decode($recom_date) AS $c){
                if($c->recom_date!=''){
                    $update_recomdate=JORLaborDetails::where('id',$c->id)->where('status','!=','Cancelled')->update([
                        'recom_date'=>$c->recom_date,
                        'recom_status'=>'Open',
                    ]);
                }
            }
        }
    }

    public function update_jor_material_recomdate(Request $request, $id){
        $recom_date=$request->input("recom_date");
        if(count(json_decode($recom_date))>0){
            foreach(json_decode($recom_date) AS $c){
                $update_recomdate=JORMaterialDetails::where('id',$id)->where('status','!=','Cancelled')->update([
                    'recom_date'=>$c,
                    'recom_status'=>'Open',
                ]);
            }
        }
    }

    public function update_jor_material_recomdate_view(Request $request){
        $recom_date=$request->input("recom_date");
        if(count(json_decode($recom_date))>0){
            foreach(json_decode($recom_date) AS $c){
                if($c->recom_date!=''){
                    $update_recomdate=JORMaterialDetails::where('id',$c->id)->where('status','!=','Cancelled')->update([
                        'recom_date'=>$c->recom_date,
                        'recom_status'=>'Open',
                    ]);
                }
            }
        }
    }

    public function save_jor_upload(Request $request, $id){
        $jorlabordetails=$request->input("jorlabordetails");
        $scope_list=$request->input("scope_list");
        $jormaterialdetails=$request->input("jormaterialdetails");
        $item_list=$request->input("item_list");
        $jornotes=$request->input("jornotes");
        $notes_list=$request->input("notes_list");
        $insertjorhead=JORHead::where('id',$request->id)->first();
        $requestor=User::where('id',$request->requestor)->value('name');
        $department_name=Departments::where('id',$request->department_id)->value('department_name');
        $department_code=Departments::where('id',$request->department_id)->value('department_code');
        $data_head=[
            'location'=>($request->location!='undefined' && $request->location!='null' && $request->location!='') ? $request->location : '',
            'jor_no'=>$request->jor_no,
            'site_jor'=>($request->site_jor!='undefined' && $request->site_jor!='null' && $request->site_jor!='') ? $request->site_jor : '',
            'duration'=>($request->duration!='undefined' && $request->duration!='null' && $request->duration!='') ? $request->duration : '',
            'completion_date'=>($request->completion_date!='undefined' && $request->completion_date!='null' && $request->completion_date!='') ? $request->completion_date : '',
            'delivery_date'=>($request->delivery_date!='undefined' && $request->delivery_date!='null' && $request->delivery_date!='') ? $request->delivery_date : '',
            'date_prepared'=>($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? $request->date_prepared : '',
            'department_id'=>$request->department_id,
            'department_name'=>$department_name,
            'dept_code'=>$department_code,
            'urgency'=>($request->urgency!='undefined' && $request->urgency!='null' && $request->urgency!='') ? $request->urgency : '',
            'process_code'=>($request->process_code!='undefined' && $request->process_code!='null' && $request->process_code!='') ? $request->process_code : '',
            'requestor_id'=>($request->requestor!='undefined' && $request->requestor!='null' && $request->requestor!='') ? $request->requestor : '',
            'requestor'=>$requestor,
            'purpose'=>($request->purpose!='undefined' && $request->purpose!='null' && $request->purpose!='') ? $request->purpose : '',
            'status'=>'Saved',
            'project_activity'=>($request->project_activity!='undefined' && $request->project_activity!='null' && $request->project_activity!='') ? $request->project_activity : '',
            'general_description'=>($request->general_description!='undefined' && $request->general_description!='null' && $request->general_description!='') ? $request->general_description : '',
            'user_id'=>Auth::id(),
        ];    
        $insertjorhead->update($data_head);  
        foreach(json_decode($jorlabordetails) AS $jl){
            $insertjorlabordetails=JORLaborDetails::where('id',$jl->id)->first();
            $data_labor_details=[
                'quantity'=>$jl->quantity,
                'uom'=>$jl->uom,
                'scope_of_work'=>$jl->scope_of_work,
                'unit_cost'=>$jl->unit_cost,
                'total_cost'=>$jl->unit_cost * $jl->quantity,
                'status'=>'Saved',
            ];  
            $insertjorlabordetails->update($data_labor_details);  
        }
        foreach(json_decode($scope_list) AS $sl){
            $data=[
                'jor_head_id'=>$request->id,
                'quantity'=>$sl->scope_qty,
                'uom'=>$sl->scope_uom,
                'scope_of_work'=>$sl->scope_work,
                'unit_cost'=>$sl->scope_unit_cost,
                'total_cost'=>$sl->scope_unit_cost * $sl->scope_qty,
                'recom_date'=>$sl->scope_recom_date,
                'recom_status'=>($sl->scope_recom_date!='' || $sl->scope_recom_date!='undefined' || $sl->scope_recom_date!='null') ? 'Open' : '',
                'status'=>'Saved',
            ];
            $jor_labor_details_id=JORLaborDetails::create($data);
        }
        foreach(json_decode($jormaterialdetails) AS $jm){
            $insertjormaterialdetails=JORMaterialDetails::where('id',$jm->id)->first();
            $data_material_details=[
                'quantity'=>$jm->quantity,
                'uom'=>$jm->uom,
                'pn_no'=>$jm->pn_no,
                'item_description'=>$jm->item_description,
                'wh_stocks'=>$jm->wh_stocks,
                'date_needed'=>$jm->date_needed,
                'status'=>'Saved',
            ];  
            $insertjormaterialdetails->update($data_material_details);  
        }
        foreach(json_decode($item_list) AS $il){
            $data=[
                'jor_head_id'=>$request->id,
                'quantity'=>$il->qty,
                'uom'=>$il->uom,
                'pn_no'=>$il->pn_no,
                'item_description'=>$il->item_desc,
                'wh_stocks'=>$il->wh_stocks,
                'date_needed'=>$il->date_needed,
                'recom_date'=>$il->recom_date,
                'recom_status'=>($il->recom_date!='' || $il->recom_date!='undefined' || $il->recom_date!='null') ? 'Open' : '',
                'status'=>'Saved',
            ];
            $jor_labor_details_id=JORMaterialDetails::create($data);
        }

        foreach(json_decode($jornotes) AS $jn){
            $jornotes=JORNotes::where('id',$jn->id)->first();
            $data_notes=[
                'status'=>'Saved',
            ];  
            $jornotes->update($data_notes);  
        }
        foreach(json_decode($notes_list) AS $nl){
            $data=[
                'jor_head_id'=>$request->id,
                'notes'=>$nl->notes,
                'status'=>'Saved',
            ];
            $jor_labor_details_id=JORNotes::create($data);
        }
    }

    public function save_jor_upload_draft(Request $request, $id){
        $jorlabordetails=$request->input("jorlabordetails");
        $scope_list=$request->input("scope_list");
        $jormaterialdetails=$request->input("jormaterialdetails");
        $item_list=$request->input("item_list");
        $jornotes=$request->input("jornotes");
        $notes_list=$request->input("notes_list");
        $requestor=User::where('id',$request->requestor)->value('name');
        $insertjorrhead=JORHead::where('id',$request->id)->first();
        if($request->props_id!=0){
            $year= ($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? date("Y", strtotime($request->date_prepared)) : date('Y');
            $year_short = ($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? date("y", strtotime($request->date_prepared)) : date('y');
            $jor_no=explode('-',$request->jor_no);
            $department_name=Departments::where('id',$request->department_id)->value('department_name');
            $department_code=Departments::where('id',$request->department_id)->value('department_code');
            $series_rows = JORSeries::where('year',$year)->count();
            $exp=explode('-',$request->jor_no);
            if($series_rows==0){
                $max_series='1';
                $jor_series='0001';
                $jor_no = 'JOR'.$department_code.$year_short."-".$jor_series;
            } else {
                $max_series=JORSeries::where('year',$year)->max('series');
                $jor_series=$max_series+1;
                $jor_no = 'JOR'.$department_code.$year_short."-".$exp[1];
                // Str::padLeft($exp, 4,'000')
            }
            if(!JORSeries::where('year',$year)->where('series',$exp[1])->exists()){
                $series['year']=$year;
                $series['series']=$jor_series;
                $jor_series=JORSeries::create($series);
            }
        }else{
            $department_name=Departments::where('id',$request->department_id)->value('department_name');
            $department_code=Departments::where('id',$request->department_id)->value('department_code');
        }
        $data_head=[
            'location'=>($request->location!='undefined' && $request->location!='null' && $request->location!='') ? $request->location : '',
            'jor_no'=>($request->props_id==0) ? $request->jor_no : $jor_no,
            'site_jor'=>($request->site_jor!='undefined' && $request->site_jor!='null' && $request->site_jor!='') ? $request->site_jor : '',
            'duration'=>($request->duration!='undefined' && $request->duration!='null' && $request->duration!='') ? $request->duration : '',
            'completion_date'=>($request->completion_date!='undefined' && $request->completion_date!='null' && $request->completion_date!='') ? $request->completion_date : '',
            'delivery_date'=>($request->delivery_date!='undefined' && $request->delivery_date!='null' && $request->delivery_date!='') ? $request->delivery_date : '',
            'date_prepared'=>($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? $request->date_prepared : '',
            'department_id'=>($request->department_id!='undefined' && $request->department_id!='null' && $request->department_id!='') ? $request->department_id : '',
            'department_name'=>$department_name,
            'dept_code'=>$department_code,
            'urgency'=>($request->urgency!='undefined' && $request->urgency!='null' && $request->urgency!='') ? $request->urgency : '',
            'process_code'=>($request->process_code!='undefined' && $request->process_code!='null' && $request->process_code!='') ? $request->process_code : '',
            'requestor_id'=>($request->requestor!='undefined' && $request->requestor!='null' && $request->requestor!='0') ? $request->requestor : '',
            'requestor'=>$requestor,
            'purpose'=>($request->purpose!='undefined' && $request->purpose!='null' && $request->purpose!='') ? $request->purpose : '',
            'status'=>'Draft',
            'project_activity'=>($request->project_activity!='undefined' && $request->project_activity!='null' && $request->project_activity!='') ? $request->project_activity : '',
            'general_description'=>($request->general_description!='undefined' && $request->general_description!='null' && $request->general_description!='') ? $request->general_description : '',
            'user_id'=>Auth::id(),
        ];    
        $insertjorrhead->update($data_head); 
        foreach(json_decode($jorlabordetails) AS $jd){
            $insertjorlabordetails=JORLaborDetails::where('id',$jd->id)->first();
            $data_labor_details=[
                'status'=>'Draft',
            ];  
            $insertjorlabordetails->update($data_labor_details);  
        }
        foreach(json_decode($scope_list) AS $sl){
            $data_labor=[
                'jor_head_id'=>$request->id,
                'quantity'=>$sl->scope_qty,
                'uom'=>$sl->scope_uom,
                'scope_of_work'=>$sl->scope_work,
                'unit_cost'=>$sl->scope_unit_cost,
                'total_cost'=>$sl->scope_unit_cost * $sl->scope_qty,
                'recom_date'=>$sl->scope_recom_date,
                'recom_status'=>($sl->scope_recom_date!='' || $sl->scope_recom_date!='undefined' || $sl->scope_recom_date!='null') ? 'Open' : '',
                'status'=>'Draft',
            ];
            $jorlabordetails_id=JORLaborDetails::create($data_labor);
        }
        foreach(json_decode($jormaterialdetails) AS $jm){
            $insertjormaterialdetails=JORMaterialDetails::where('id',$jm->id)->first();
            $data_material_details=[
                'status'=>'Draft',
            ];  
            $insertjormaterialdetails->update($data_material_details);  
        }
        foreach(json_decode($item_list) AS $il){
            $data_material=[
                'jor_head_id'=>$request->id,
                'quantity'=>$il->qty,
                'uom'=>$il->uom,
                'pn_no'=>$il->pn_no,
                'item_description'=>$il->item_desc,
                'wh_stocks'=>$il->wh_stocks,
                'date_needed'=>$il->date_needed,
                'recom_date'=>$il->recom_date,
                'recom_status'=>($il->recom_date!='' || $il->recom_date!='undefined' || $il->recom_date!='null') ? 'Open' : '',
                'status'=>'Draft',
            ];
            $jormaterialdetails_id=JORMaterialDetails::create($data_material);
        }
        foreach(json_decode($jornotes) AS $jn){
            $jornotes=JORNotes::where('id',$jn->id)->first();
            $data_notes_details=[
                'status'=>'Draft',
            ];  
            $jornotes->update($data_notes_details);  
        }
        foreach(json_decode($notes_list) AS $in){
            $data_notes=[
                'jor_head_id'=>$request->id,
                'notes'=>$in->notes,
                'status'=>'Draft',
            ];
            $jornotes_id=JORNotes::create($data_notes);
        }
    }

    public function cancel_jor(Request $request, $jor_head_id){
        $update_jorhead=JORHead::where('id',$jor_head_id)->first();
        $updatehead['status']='Cancelled';
        $updatehead['cancelled_by']=Auth::id();
        $updatehead['cancelled_date']=date('Y-m-d h:i:s');
        $update_jorhead->update($updatehead);
        if($update_jorhead){
            $update_labordetails=JORLaborDetails::where('jor_head_id',$jor_head_id)->update([
                'status'=>'Cancelled',
                'cancelled_by'=>Auth::id(),
                'cancelled_date'=>date('Y-m-d h:i:s')
            ]);
            $update_materialdetails=JORMaterialDetails::where('jor_head_id',$jor_head_id)->update([
                'status'=>'Cancelled',
                'cancelled_by'=>Auth::id(),
                'cancelled_date'=>date('Y-m-d h:i:s')
            ]);
            $update_notes=JORNotes::where('jor_head_id',$jor_head_id)->update([
                'status'=>'Cancelled',
                'cancelled_by'=>Auth::id(),
                'cancelled_date'=>date('Y-m-d h:i:s')
            ]);
        }
    }

    public function create_jor(Request $request){
        $formData=[
            'jo_request'=>'',
            'jor_no'=>'',
            'site_jor'=>'',
            'duration'=>'',
            'completion_date'=>'',
            'delivery_date'=>'',
            'date_prepared'=>'',
            'department'=>'',
            'urgency'=>0,
            'process_code'=>'',
            'purpose'=>'',
            'requestor'=>0,
            'project_activity'=>'',
            'general_description'=>'',
        ];
        return response()->json($formData);
    }

    public function save_jor_manual(Request $request){
        if(!JORHead::where('site_jor',$request->site_jor)->where('status','Saved')->exists()){
            $scope_list=$request->input("scope_list");
            $requestor=User::where('id',$request->requestor)->value('name');
            $item_list=$request->input("item_list");
            $notes_list=$request->input("notes_list");
            $year= ($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? date("Y", strtotime($request->date_prepared)) : date('Y');
            $year_short = ($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? date("y", strtotime($request->date_prepared)) : date('y');
            $department_name=Departments::where('id',$request->department_id)->value('department_name');
            $department_code=Departments::where('id',$request->department_id)->value('department_code');
            $series_rows = JORSeries::where('year',$year)->count();
            $data_head=$this->validate($request,
                [
                    'jor_no'=>'required|string',
                    'department_id'=>'required|integer',
                    'location'=>'required|string',
                    'date_prepared'=>'required|string',
                    // 'requestor'=>'required|string',
                    'requestor'=>'required|integer|gt:0',
                    'purpose'=>'required|string',
                    'general_description'=>'required|string'

                ],
                [
                'department_id.required'=> 'The department field is required.',
                'gt'=> 'The :attribute field is required.',
                //    'location.required'=> 'The location field is required.',
                //    'date_prepared.required'=> 'The date prepared field is required.',
                //    'requestor.required'=> 'The requestor field is required.',
                //    'purpose.required'=> 'The purpose field is required.',
                //    'general_description.required'=> 'The general description field is required.',
                ]
            );
            // $data_head['location']=($request->location!='undefined' && $request->location!='null' && $request->location!='') ? $request->location : '';
            $data_head['site_jor']=($request->site_jor!='undefined' && $request->site_jor!='null' && $request->site_jor!='') ? $request->site_jor : '';
            $data_head['duration']=($request->duration!='undefined' && $request->duration!='null' && $request->duration!='') ? $request->duration : '';
            $data_head['completion_date']=($request->completion_date!='undefined' && $request->completion_date!='null' && $request->completion_date!='') ? $request->completion_date : '';
            $data_head['delivery_date']=($request->delivery_date!='undefined' && $request->delivery_date!='null' && $request->delivery_date!='') ? $request->delivery_date : '';
            // $data_head['date_prepared']=($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? $request->date_prepared : '';
            $data_head['department_name']=$department_name;
            $data_head['dept_code']=$department_code;
            $data_head['urgency']=($request->urgency!='undefined' && $request->urgency!='null' && $request->urgency!='') ? $request->urgency : '';
            $data_head['process_code']=($request->process_code!='undefined' && $request->process_code!='null' && $request->process_code!='') ? $request->process_code : '';
            $data_head['requestor_id']=($request->requestor!='undefined' && $request->requestor!='null' && $request->requestor!='0') ? $request->requestor : '';
            $data_head['requestor']=$requestor;
            // $data_head['requestor']=($request->requestor!='undefined' && $request->requestor!='null' && $request->requestor!='') ? $request->requestor : '';
            // $data_head['purpose']=($request->purpose!='undefined' && $request->purpose!='null' && $request->purpose!='') ? $request->purpose : '';
            $data_head['method']='Manual';
            $data_head['status']='Saved';
            // $data_head['general_description']=($request->general_description!='undefined' && $request->general_description!='null' && $request->general_description!='') ? $request->general_description : '';
            $data_head['project_activity']=($request->project_activity!='undefined' && $request->project_activity!='null' && $request->project_activity!='') ? $request->project_activity : '';
            $data_head['user_id']=Auth::id();
            if($request->jorhead_id==0){
                $insertjorhead=JORHead::create($data_head);
            }else{
                $insertjorhead=JORHead::where('id',$request->jorhead_id)->first();
                $insertjorhead->update($data_head);
            }
            $exp=explode('-',$request->jor_no);
            if($series_rows==0){
                $max_series='1';
                $jor_series='0001';
                $jor_no = 'JOR'.$department_code.$year_short."-".$jor_series;
            } else {
                $max_series=JORSeries::where('year',$year)->max('series');
                $jor_series=$max_series+1;
                $jor_no = 'JOR'.$department_code.$year_short."-".Str::padLeft($exp[1], 4,'000');
            }
            if(!JORSeries::where('year',$year)->where('series',$exp[1])->exists()){
                $series['year']=$year;
                $series['series']=$jor_series;
                $jor_series=JORSeries::create($series);
            }

            foreach(json_decode($scope_list) AS $sl){
                $data=[
                    'jor_head_id'=>$insertjorhead->id,
                    'quantity'=>$sl->scope_qty,
                    'uom'=>$sl->scope_uom,
                    'scope_of_work'=>$sl->scope_work,
                    'unit_cost'=>$sl->scope_unit_cost,
                    'total_cost'=>$sl->scope_unit_cost * $sl->scope_qty,
                    'recom_date'=>$sl->scope_recom_date,
                    'recom_status'=>($sl->scope_recom_date!='' && $sl->scope_recom_date!='undefined' && $sl->scope_recom_date!='null') ? 'Open' : '',
                    'status'=>'Saved',
                ];
                if($request->jorhead_id==0){
                    $jorlabordetails_id=JORLaborDetails::create($data);
                }else{
                    if(!JORLaborDetails::where('jor_head_id',$request->jorhead_id)->where('scope_of_work',$sl->scope_work)->where('uom',$sl->scope_uom)->where('quantity',$sl->scope_qty)->where('unit_cost',$sl->scope_unit_cost)->exists()){
                        $jorlabordetails_id=JORLaborDetails::create($data);
                    }else{
                        $jorlabordetails_id=JORLaborDetails::updateOrCreate(
                            [
                                'scope_of_work'=> $sl->scope_work,
                                'quantity'=>$sl->scope_qty,
                                'uom'=>$sl->scope_uom,
                                'unit_cost'=>$sl->scope_unit_cost,
                                'total_cost'=>$sl->scope_unit_cost * $sl->scope_qty,
                                'recom_date'=>$sl->scope_recom_date,
                            ],
                            [
                                'jor_head_id'=>$insertjorhead->id,
                                'quantity'=>$sl->scope_qty,
                                'uom'=>$sl->scope_uom,
                                'scope_of_work'=>$sl->scope_work,
                                'unit_cost'=>$sl->scope_unit_cost,
                                'total_cost'=>$sl->scope_unit_cost * $sl->scope_qty,
                                'recom_date'=>$sl->scope_recom_date,
                                'recom_status'=>($sl->scope_recom_date!='' && $sl->scope_recom_date!='undefined' && $sl->scope_recom_date!='null') ? 'Open' : '',
                                'status'=>'Saved',
                            ]
                        );
                    }
                }
            }

            foreach(json_decode($item_list) AS $il){
                $data=[
                    'jor_head_id'=>$insertjorhead->id,
                    'quantity'=>$il->qty,
                    'uom'=>$il->uom,
                    'pn_no'=>$il->pn_no,
                    'item_description'=>$il->item_desc,
                    'wh_stocks'=>$il->wh_stocks,
                    'date_needed'=>$il->date_needed,
                    'recom_date'=>$il->recom_date,
                    'recom_status'=>($il->recom_date!='' && $il->recom_date!='undefined' && $il->recom_date!='null') ? 'Open' : '',
                    'status'=>'Saved',
                ];
                if($request->jorhead_id==0){
                    $jormaterialdetails_id=JORMaterialDetails::create($data);
                }else{
                    if(!JORMaterialDetails::where('jor_head_id',$request->jorhead_id)->where('uom',$il->uom)->where('pn_no',$il->pn_no)->where('item_description',$il->item_desc)->where('quantity',$il->qty)->exists()){
                        $jormaterialdetails_id=JORMaterialDetails::create($data);
                    }else{
                        $jormaterialdetails_id=JORMaterialDetails::updateOrCreate(
                            [
                                'item_description'=> $il->item_desc,
                                'pn_no'=>$il->pn_no,
                                'quantity'=>$il->qty,
                                'uom'=>$il->uom,
                                'date_needed'=>$il->date_needed,
                                'recom_date'=>$il->recom_date,
                            ],
                            [
                                'jor_head_id'=>$insertjorhead->id,
                                'quantity'=>$il->qty,
                                'uom'=>$il->uom,
                                'pn_no'=>$il->pn_no,
                                'item_description'=>$il->item_desc,
                                'wh_stocks'=>$il->wh_stocks,
                                'date_needed'=>$il->date_needed,
                                'recom_date'=>$il->recom_date,
                                'recom_status'=>($il->recom_date!='' && $il->recom_date!='undefined' && $il->recom_date!='null') ? 'Open' : '',
                                'status'=>'Saved',
                            ]
                        );
                    }
                }
            }

            foreach(json_decode($notes_list) AS $nl){
                $data=[
                    'jor_head_id'=>$insertjorhead->id,
                    'notes'=>$nl->notes,
                    'status'=>'Saved',
                ];
                if($request->jorhead_id==0){
                    $jornotes_id=JORNotes::create($data);
                }else{
                    if(!JORNotes::where('jor_head_id',$request->jorhead_id)->where('notes',$nl->notes)->exists()){
                        $jornotes_id=JORNotes::create($data);
                    }else{
                        $jornotes_id=JORNotes::updateOrCreate(
                            [
                                'notes'=>$nl->notes,
                            ],
                            [
                                'jor_head_id'=>$insertjorhead->id,
                                'notes'=>$nl->notes,
                                'status'=>'Saved',
                            ]
                        );
                    }
                }
            }
            return $insertjorhead->id;
        }else{
            echo 'error';
        }
    }

    public function save_jor_manual_draft(Request $request){
        if(!JORHead::where('site_jor',$request->site_jor)->where('status','Saved')->exists()){
            $scope_list=$request->input("scope_list");
            $item_list=$request->input("item_list");
            $notes_list=$request->input("notes_list");
            $year= ($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? date("Y", strtotime($request->date_prepared)) : date('Y');
            $year_short = ($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? date("y", strtotime($request->date_prepared)) : date('y');
            $department_name=Departments::where('id',$request->department_id)->value('department_name');
            $department_code=Departments::where('id',$request->department_id)->value('department_code');
            $series_rows = JORSeries::where('year',$year)->count();
            $requestor=User::where('id',$request->requestor)->value('name');
            $data_head=$this->validate($request,
                [
                    'jor_no'=>'required|string',
                    'department_id'=>'required|integer'
                ],
                [
                'department_id.required'=> 'The department field is required.'
                ]
            );
            $data_head['location']=($request->location!='undefined' && $request->location!='null' && $request->location!='') ? $request->location : '';
            $data_head['site_jor']=($request->site_jor!='undefined' && $request->site_jor!='null' && $request->site_jor!='') ? $request->site_jor : '';
            $data_head['duration']=($request->duration!='undefined' && $request->duration!='null' && $request->duration!='') ? $request->duration : '';
            $data_head['completion_date']=($request->completion_date!='undefined' && $request->completion_date!='null' && $request->completion_date!='') ? $request->completion_date : '';
            $data_head['delivery_date']=($request->delivery_date!='undefined' && $request->delivery_date!='null' && $request->delivery_date!='') ? $request->delivery_date : '';
            $data_head['date_prepared']=($request->date_prepared!='undefined' && $request->date_prepared!='null' && $request->date_prepared!='') ? $request->date_prepared : '';
            $data_head['department_name']=$department_name;
            $data_head['dept_code']=$department_code;
            $data_head['urgency']=($request->urgency!='undefined' && $request->urgency!='null' && $request->urgency!='') ? $request->urgency : '';
            $data_head['process_code']=($request->process_code!='undefined' && $request->process_code!='null' && $request->process_code!='') ? $request->process_code : '';
            $data_head['requestor_id']=($request->requestor!='undefined' && $request->requestor!='null' && $request->requestor!='0') ? $request->requestor : '';
            $data_head['requestor']=$requestor;
            $data_head['purpose']=($request->purpose!='undefined' && $request->purpose!='null' && $request->purpose!='') ? $request->purpose : '';
            $data_head['method']='Manual';
            $data_head['status']='Saved';
            $data_head['general_description']=($request->general_description!='undefined' && $request->general_description!='null' && $request->general_description!='') ? $request->general_description : '';
            $data_head['project_activity']=($request->project_activity!='undefined' && $request->project_activity!='null' && $request->project_activity!='') ? $request->project_activity : '';
            $data_head['user_id']=Auth::id();
            if($request->jorhead_id==0){
                $insertjorhead=JORHead::create($data_head);
            }else{
                $insertjorhead=JORHead::where('id',$request->jorhead_id)->first();
                $insertjorhead->update($data_head);
            }
            $exp=explode('-',$request->jor_no);
            if($series_rows==0){
                $max_series='1';
                $jor_series='0001';
                $jor_no = 'JOR'.$department_code.$year_short."-".$jor_series;
            } else {
                $max_series=JORSeries::where('year',$year)->max('series');
                $jor_series=$max_series+1;
                $jor_no = 'JOR'.$department_code.$year_short."-".$exp[1];
            }
            if(!JORSeries::where('year',$year)->where('series',$exp[1])->exists()){
                $series['year']=$year;
                $series['series']=$jor_series;
                $jor_series=JORSeries::create($series);
            }
            foreach(json_decode($scope_list) AS $sl){
                $data=[
                    'jor_head_id'=>$insertjorhead->id,
                    'quantity'=>$sl->scope_qty,
                    'uom'=>$sl->scope_uom,
                    'scope_of_work'=>$sl->scope_work,
                    'unit_cost'=>$sl->scope_unit_cost,
                    'total_cost'=>$sl->scope_unit_cost * $sl->scope_qty,
                    'recom_date'=>$sl->scope_recom_date,
                    'recom_status'=>($sl->scope_recom_date!='' && $sl->scope_recom_date!='undefined' && $sl->scope_recom_date!='null') ? 'Open' : '',
                    'status'=>'Draft',
                ];
                if($request->jorhead_id==0){ 
                    $jorlabordetails_id=JORLaborDetails::create($data);
                }else{
                    if(!JORLaborDetails::where('jor_head_id',$request->jorhead_id)->where('scope_of_work',$sl->scope_work)->where('uom',$sl->scope_uom)->where('quantity',$sl->scope_qty)->where('unit_cost',$sl->scope_unit_cost)->exists()){
                        $jorlabordetails_id=JORLaborDetails::create($data);
                    }else{
                        $jorlabordetails_id=JORLaborDetails::updateOrCreate(
                            [
                                'quantity'=>$sl->scope_qty,
                                'uom'=>$sl->scope_uom,
                                'scope_of_work'=>$sl->scope_work,
                                'unit_cost'=>$sl->scope_unit_cost,
                                'total_cost'=>$sl->scope_unit_cost * $sl->scope_qty,
                                'recom_date'=>$sl->scope_recom_date,
                            ],
                            [
                                'jor_head_id'=>$insertjorhead->id,
                                'quantity'=>$sl->scope_qty,
                                'uom'=>$sl->scope_uom,
                                'scope_of_work'=>$sl->scope_work,
                                'unit_cost'=>$sl->scope_unit_cost,
                                'total_cost'=>$sl->scope_unit_cost * $sl->scope_qty,
                                'recom_date'=>$sl->scope_recom_date,
                                'recom_status'=>($sl->scope_recom_date!='' && $sl->scope_recom_date!='undefined' && $sl->scope_recom_date!='null') ? 'Open' : '',
                                'status'=>'Draft',
                            ]
                        );
                    }
                }
            }

            foreach(json_decode($item_list) AS $il){
                $data=[
                    'jor_head_id'=>$insertjorhead->id,
                    'quantity'=>$il->qty,
                    'uom'=>$il->uom,
                    'pn_no'=>$il->pn_no,
                    'item_description'=>$il->item_desc,
                    'wh_stocks'=>$il->wh_stocks,
                    'date_needed'=>$il->date_needed,
                    'recom_date'=>$il->recom_date,
                    'recom_status'=>($il->recom_date!='' && $il->recom_date!='undefined' && $il->recom_date!='null') ? 'Open' : '',
                    'status'=>'Draft',
                ];
                if($request->jorhead_id==0){ 
                    $jormaterialdetails_id=JORMaterialDetails::create($data);
                }else{
                    if(!JORMaterialDetails::where('jor_head_id',$request->jorhead_id)->where('uom',$il->uom)->where('pn_no',$il->pn_no)->where('item_description',$il->item_desc)->where('quantity',$il->qty)->exists()){
                        $jormaterialdetails_id=JORMaterialDetails::create($data);
                    }else{
                        $jormaterialdetails_id=JORMaterialDetails::updateOrCreate(
                            [
                                'item_description'   => $il->item_desc,
                                'pn_no'=>$il->pn_no,
                                'quantity'=>$il->qty,
                                'uom'=>$il->uom,
                                'date_needed'=>$il->date_needed,
                                'recom_date'=>$il->recom_date,
                            ],
                            [
                                'jor_head_id'=>$insertjorhead->id,
                                'quantity'=>$il->qty,
                                'uom'=>$il->uom,
                                'pn_no'=>$il->pn_no,
                                'item_description'=>$il->item_desc,
                                'wh_stocks'=>$il->wh_stocks,
                                'date_needed'=>$il->date_needed,
                                'recom_date'=>$il->recom_date,
                                'recom_status'=>($il->recom_date!='' && $il->recom_date!='undefined' && $il->recom_date!='null') ? 'Open' : '',
                                'status'=>'Draft',
                            ]
                        );
                    }
                }
            }
            foreach(json_decode($notes_list) AS $nl){
                $data=[
                    'jor_head_id'=>$insertjorhead->id,
                    'notes'=>$nl->notes,
                    'status'=>'Draft',
                ];
                if($request->jorhead_id==0){ 
                    $jornotes_id=JORNotes::create($data);
                }else{
                    if(!JORNotes::where('jor_head_id',$request->jorhead_id)->where('notes',$nl->notes)->exists()){
                        $jornotes_id=JORNotes::create($data);
                    }else{
                        $jornotes_id=JORNotes::updateOrCreate(
                            [
                                'notes'=> $nl->notes,
                            ],
                            [
                                'jor_head_id'=>$insertjorhead->id,
                                'notes'=>$nl->notes,
                                'status'=>'Draft',
                            ]
                        );
                    }
                }
            }
            return $insertjorhead->id;
        }else{
            echo 'error';
        }
    }

    public function get_alljor(){
        $jor=JORHead::orderBy('jor_no','ASC')->get();
        $jorall=[];
        foreach($jor AS $j){
            $jorall[]=[
                'id'=>$j->id,
                'status'=>$j->status,
                $j->jor_no,
                $j->date_prepared,
                date('Y-m-d',strtotime($j->created_at)),
                $j->department_name,
                $j->urgency,
                $j->requestor,
                $j->status,
                ''
            ];
        }
        return response()->json([
            'jorall'=>$jorall,
        ],200);
    }

    public function get_jor_view_details(Request $request, $jor_head_id){
        $jorhead=JORHead::where('id',$jor_head_id)->first();
        $cancelled_by=User::where('id',$jorhead->cancelled_by)->value('name');
        $jorlabordetails=JORLaborDetails::where('jor_head_id',$jor_head_id)->get();
        $jomaterialdetails=JORMaterialDetails::where('jor_head_id',$jor_head_id)->get();
        $jornotes=JORNotes::where('jor_head_id',$jor_head_id)->get();
        return response()->json([
            'jorhead'=>$jorhead,
            'jorlabordetails'=>$jorlabordetails,
            'jomaterialdetails'=>$jomaterialdetails,
            'jornotes'=>$jornotes,
            'cancelled_by_all'=>$cancelled_by,
        ],200);
    }

    public function cancel_alljor(Request $request, $jor_head_id){
        $update_jorhead=JORHead::where('id',$jor_head_id)->first();
        $updatehead['status']='Cancelled';
        $updatehead['cancelled_by']=Auth::id();
        $updatehead['cancelled_date']=date('Y-m-d H:i:s');
        $update_jorhead->update($updatehead);
        if($update_jorhead){
            $labordetails_loop=JORLaborDetails::where('jor_head_id',$jor_head_id)->get();
            $materialdetails_loop=JORMaterialDetails::where('jor_head_id',$jor_head_id)->get();
            $notedetails_loop=JORNotes::where('jor_head_id',$jor_head_id)->get();
            foreach($labordetails_loop AS $lab){
                $cancelled_labor_date=JORLaborDetails::where('id',$lab->id)->value('cancelled_date');
                if($cancelled_labor_date!='' && $cancelled_labor_date!=null){
                    $update_jorlabordetails=JORLaborDetails::where('id',$lab->id)->update([
                        'status'=>'Cancelled',
                    ]);
                }else{
                    $update_jorlabordetails=JORLaborDetails::where('id',$lab->id)->update([
                        'status'=>'Cancelled',
                        'cancelled_by'=>Auth::id(),
                        'cancelled_date'=>date('Y-m-d H:i:s'),
                        'cancelled_reason'=>'Cancelled JOR',
                    ]);
                }
            }

            foreach($materialdetails_loop AS $ml){
                $cancelled_material_date=JORMaterialDetails::where('id',$ml->id)->value('cancelled_date');
                if($cancelled_material_date!='' && $cancelled_material_date!=null){
                    $update_jormaterialdetails=JORMaterialDetails::where('id',$ml->id)->update([
                        'status'=>'Cancelled',
                    ]);
                }else{
                    $update_jormaterialdetails=JORMaterialDetails::where('id',$ml->id)->update([
                        'status'=>'Cancelled',
                        'cancelled_by'=>Auth::id(),
                        'cancelled_date'=>date('Y-m-d H:i:s'),
                        'cancelled_reason'=>'Cancelled JOR',
                    ]);
                }
            }
            foreach($notedetails_loop AS $nl){
                $cancelled_notes_date=JORNotes::where('id',$nl->id)->value('cancelled_date');
                if($cancelled_notes_date!='' && $cancelled_notes_date!=null){
                    $update_jornotes=JORNotes::where('id',$nl->id)->update([
                        'status'=>'Cancelled',
                    ]);
                }else{
                    $update_jornotes=JORNotes::where('id',$nl->id)->update([
                        'status'=>'Cancelled',
                        'cancelled_by'=>Auth::id(),
                        'cancelled_date'=>date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }

    public function cancel_jorlabordetails(Request $request, $jor_labor_details_id){
        if(!JOILaborDetails::where('jor_labor_details_id',$jor_labor_details_id)->exists()){
            $update_jorlabordetails=JORLaborDetails::where('id',$jor_labor_details_id)->update([
                'status'=>'Cancelled',
                'cancelled_date'=>date('Y-m-d H:i:s'),
                'cancelled_reason'=>$request->cancel_reason,
                'cancelled_by'=>Auth::id(),
            ]);
        }else{
            echo 'error';
        }
    }

    public function cancel_jormaterialdetails(Request $request, $jor_material_details_id){
        if(!JOIMaterialDetails::where('jor_material_details_id',$jor_material_details_id)->exists()){
            $update_jormaterialdetails=JORMaterialDetails::where('id',$jor_material_details_id)->update([
                'status'=>'Cancelled',
                'cancelled_date'=>date('Y-m-d H:i:s'),
                'cancelled_reason'=>$request->cancel_reason,
                'cancelled_by'=>Auth::id(),
            ]);
        }else{
            echo 'error';
        }
    }

    public function cancel_jornotes($jor_notes_id){
        $update_jornotedetails=JORNotes::where('id',$jor_notes_id)->update([
            'status'=>'Cancelled',
            'cancelled_date'=>date('Y-m-d H:i:s'),
            'cancelled_by'=>Auth::id(),
        ]);
    }

    public function cancelled_labor_data($jorhead_id,$jorlabordetails_id){
        $laborcancelled = JORLaborDetails::where('id',$jorlabordetails_id)->where('jor_head_id',$jorhead_id)->where('status','Cancelled')->first();
        $labor_cancelled=($laborcancelled->cancelled_by!='' && $laborcancelled->cancelled_by!=null) ? $laborcancelled->cancelled_by : 0 ;
        $cancelled_by_labor= User::where('id',$labor_cancelled)->value('name');
        return response()->json([
            'cancelled'=>$laborcancelled,
            'cancelled_by'=>$cancelled_by_labor,
            'identifier'=>'Scope of work',
        ],200);
    }

    public function cancelled_material_data($jorhead_id,$jormaterialdetails_id){
        $materialcancelled = JORMaterialDetails::where('id',$jormaterialdetails_id)->where('jor_head_id',$jorhead_id)->where('status','Cancelled')->first();
        $material_cancelled=($materialcancelled->cancelled_by!='' && $materialcancelled->cancelled_by!=null) ? $materialcancelled->cancelled_by : 0;
        $cancelled_by_material= User::where('id',$material_cancelled)->value('name');
        return response()->json([
            'cancelled'=>$materialcancelled,
            'cancelled_by'=>$cancelled_by_material,
            'identifier'=>'Item',
        ],200);
    }
}
