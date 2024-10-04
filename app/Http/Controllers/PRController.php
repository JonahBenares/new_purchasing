<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PRImport;
use App\Imports\PRDetailsImport;
use App\Models\PRHead;
use App\Models\PRDetails;
use App\Models\PRSeries;
use App\Models\PrReportDetails;
use App\Models\Departments;
use App\Models\PettyCash;
use App\Models\User;
use App\Models\PoDetails;
use App\Models\RFQDetails;
use App\Models\RFQHead;
use App\Models\RecomReportDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class PRController extends Controller
{
    public function import_pr(Request $request){
        if($request->file('upload_pr')){
            $filename=$request->file('upload_pr')->getClientOriginalName();
            $request->file('upload_pr')->storeAs('imports',$filename);
            $user_id = auth()->user()->id;
            $prImport = new PRImport($user_id);
            $sheet1 = Excel::import($prImport, request()->file('upload_pr'));
            $head = $prImport->data;
            $pr_head_id = $prImport->id;
            $prImportdetails = new PRDetailsImport($pr_head_id);
            $sheet2 = Excel::import($prImportdetails, request()->file('upload_pr'));
            $details = $prImportdetails->data;
            return response()->json([
                'pr_head_id'=>$pr_head_id,
                'prhead'=>$head,
                'prdetails'=>$details,
            ],200);
            // Excel::import(new PRImport($user_id), request()->file('upload_pr'));
            // Excel::import(new PRDetailsImport, request()->file('upload_pr'));
        }
    }

    public function get_import_data($id){
        $pr_head = PRHead::where('id',$id)->where('status','!=','Cancelled')->get();
        $pr_details = PRDetails::where('pr_head_id',$id)->where('status','!=','Cancelled')->get();
        return response()->json([
            'pr_head'=>$pr_head,
            'pr_details'=>$pr_details,
        ],200);
    }

    public function create_pr(Request $request){
        $formData=[
            'purchase_request'=>'',
            'pr_no'=>'',
            'site_pr'=>'',
            'dae_prepared'=>'',
            'department'=>'',
            'urgency'=>0,
            'process_code'=>'',
            'enduse'=>'',
            'purpose'=>'',
            'requestor'=>'',
            'petty_cash'=>0,
        ];
        return response()->json($formData);
    }

    public function processing_code(){
        $code=array();
        foreach(range('X','Z') AS $c){
            $code[]=$c;
        }
        return response()->json($code);
    }

    public function save_upload(Request $request, $id){
        $prhead=$request->input("prhead");
        $prdetails=$request->input("prdetails");
        $item_list=$request->input("item_list");
        if(count(json_decode($prhead))>0){
            foreach(json_decode($prhead) AS $ph){
                if(count(json_decode($prhead))>0){ 
                    $insertprhead=PRHead::where('id',$ph->id)->first();
                    $department_name=Departments::where('id',$ph->department_id)->value('department_name');
                    $status=($ph->petty_cash==0) ? 'Saved' : 'Closed';
                    $data_head=[
                        'location'=>$ph->location,
                        'pr_no'=>$ph->pr_no,
                        'site_pr'=>$ph->site_pr,
                        'date_prepared'=>$ph->date_prepared,
                        'department_id'=>$ph->department_id,
                        'department_name'=>$department_name,
                        'urgency'=>$ph->urgency,
                        'process_code'=>$ph->process_code,
                        'requestor'=>$ph->requestor,
                        'enduse'=>$ph->enduse,
                        'purpose'=>$ph->purpose,
                        'status'=>$status,
                        'petty_cash'=>$ph->petty_cash,
                    ];    
                    $insertprhead->update($data_head); 
                    if($insertprhead && $ph->petty_cash==1){
                        $data_petty=[
                            'pr_head_id'=>$ph->id,
                            'pr_no'=>$ph->pr_no,
                            'prepared_by'=>$request->prepared_by,
                            'recommended_by'=>$request->recommended_by,
                            'approved_by'=>$request->approved_by,
                            'approved_date'=>$request->approved_date,
                            'remarks'=>$request->remarks,
                            'user_id'=>Auth::id(),
                        ];  
                        PettyCash::create($data_petty);
                    }   
                    foreach(json_decode($prdetails) AS $pd){
                        $insertprdetails=PRDetails::where('id',$pd->id)->first();
                        $data_details=[
                            'status'=>$status,
                        ];  
                        $insertprdetails->update($data_details);  
                    }
                    foreach(json_decode($item_list) AS $il){
                        $data=[
                            'pr_head_id'=>$ph->id,
                            'quantity'=>$il->qty,
                            'uom'=>$il->uom,
                            'pn_no'=>$il->pn_no,
                            'item_description'=>$il->item_desc,
                            'wh_stocks'=>$il->wh_stocks,
                            'date_needed'=>$il->date_needed,
                            'recom_date'=>$il->recom_date,
                            'status'=>$status,
                        ];
                        $prdetails_id=PRDetails::create($data);
                        if($prdetails_id){
                            $prreport['pr_no']=$ph->pr_no;
                            $prreport['pr_details_id']=$prdetails_id->id;
                            $prreport['item_description']=$il->item_desc;
                            $prreport['pr_qty']=$il->qty;
                            $prreport['uom']=$il->uom;
                            $prreport['status']='pending for RFQ';
                            PrReportDetails::create($prreport);
                        }
                    }
                }
            }
        }
    }

    public function save_upload_draft(Request $request, $id){
        $prhead=$request->input("prhead");
        $prdetails=$request->input("prdetails");
        $item_list=$request->input("item_list");
        if(count(json_decode($prhead))>0){
            foreach(json_decode($prhead) AS $ph){
                if(count(json_decode($prhead))>0){ 
                    $insertprhead=PRHead::where('id',$ph->id)->first();
                    // $department_name=Departments::where('id',$ph->department_id)->value('department_name');
                    if($request->props_id!=0){
                        $year= ($ph->date_prepared!='undefined') ? date("Y", strtotime($ph->date_prepared)) : date('Y');
                        $year_short = ($ph->date_prepared!='undefined') ? date("y", strtotime($ph->date_prepared)) : date('y');
                        $pr_no=explode('-',$ph->pr_no);
                        $department_name=Departments::where('id',$ph->department_id)->value('department_name');
                        $department_code=Departments::where('id',$ph->department_id)->value('department_code');
                        $series_rows = PRSeries::where('year',$year)->count();
                        $exp=explode('-',$ph->pr_no);
                        if($series_rows==0){
                            $max_series='1';
                            $pr_series='0001';
                            $pr_no = $department_code.$year_short."-".$pr_series;
                        } else {
                            $max_series=PRSeries::where('year',$year)->max('series');
                            $pr_series=$max_series+1;
                            $pr_no = $department_code.$year_short."-".$exp[1];
                            // Str::padLeft($exp, 4,'000')
                        }
                        if(!PRSeries::where('year',$year)->where('series',$exp[1])->exists()){
                            $series['year']=$year;
                            $series['series']=$pr_series;
                            $pr_series=PRSeries::create($series);
                        }
                    }else{
                        $department_name=Departments::where('id',$ph->department_id)->value('department_name');
                    }
                    $data_head=[
                        'location'=>$ph->location,
                        'pr_no'=>($request->props_id==0) ? $ph->pr_no : $pr_no,
                        'site_pr'=>$ph->site_pr,
                        'date_prepared'=>$ph->date_prepared,
                        'department_id'=>$ph->department_id,
                        'department_name'=>$department_name,
                        'urgency'=>$ph->urgency,
                        'process_code'=>$ph->process_code,
                        'requestor'=>$ph->requestor,
                        'enduse'=>$ph->enduse,
                        'purpose'=>$ph->purpose,
                        'status'=>'Draft',
                        'petty_cash'=>$ph->petty_cash,
                    ];    
                    $insertprhead->update($data_head); 
                    if($insertprhead && $ph->petty_cash==1){
                        $data_petty=[
                            'pr_head_id'=>$ph->id,
                            'pr_no'=>$ph->pr_no,
                            'prepared_by'=>$request->prepared_by,
                            'recommended_by'=>$request->recommended_by,
                            'approved_by'=>$request->approved_by,
                            'approved_date'=>$request->approved_date,
                            'remarks'=>$request->remarks,
                            'user_id'=>Auth::id(),
                        ];  
                        PettyCash::create($data_petty);
                    }   
                    foreach(json_decode($prdetails) AS $pd){
                        $insertprdetails=PRDetails::where('id',$pd->id)->first();
                        $data_details=[
                            'status'=>'Draft',
                        ];  
                        $insertprdetails->update($data_details);  
                    }
                    foreach(json_decode($item_list) AS $il){
                        $data=[
                            'pr_head_id'=>$ph->id,
                            'quantity'=>$il->qty,
                            'uom'=>$il->uom,
                            'pn_no'=>$il->pn_no,
                            'item_description'=>$il->item_desc,
                            'wh_stocks'=>$il->wh_stocks,
                            'date_needed'=>$il->date_needed,
                            'recom_date'=>$il->recom_date,
                            'status'=>'Draft',
                        ];
                        $prdetails_id=PRDetails::create($data);
                        if($prdetails_id){
                            $prreport['pr_no']=$ph->pr_no;
                            $prreport['pr_details_id']=$prdetails_id->id;
                            $prreport['item_description']=$il->item_desc;
                            $prreport['pr_qty']=$il->qty;
                            $prreport['uom']=$il->uom;
                            $prreport['status']='pending for RFQ';
                            PrReportDetails::create($prreport);
                        }
                    }
                }
            }
        }
    }

    public function update_recomdate(Request $request, $id){
        // $update_recomdate=PRDetails::where('id',$id)->where('status','!=','Cancelled')->first();
        $recom_date=$request->input("recom_date");
        if(count(json_decode($recom_date))>0){
            foreach(json_decode($recom_date) AS $c){
                $update_recomdate=PRDetails::where('id',$c->id)->where('status','!=','Cancelled')->update([
                    'recom_date'=>$c,
                    'recom_status'=>'Open',
                ]);
                // $validated['recom_date']=$c;
                // $validated['recom_status']='Open';
                // $update_recomdate->update($validated);
            }
        }
    }

    public function update_recomdate_view(Request $request){
        $recom_date=$request->input("recom_date");
        if(count(json_decode($recom_date))>0){
            foreach(json_decode($recom_date) AS $c){
                if($c->recom_date!=''){
                    $update_recomdate=PRDetails::where('id',$c->id)->where('status','!=','Cancelled')->update([
                        'recom_date'=>$c->recom_date,
                        'recom_status'=>'Open',
                    ]);
                    // $update_recomdate=PRDetails::where('id',$c->id)->where('status','!=','Cancelled')->first();
                    // $validated['recom_date']=$c->recom_date;
                    // $validated['recom_status']='Open';
                    // $update_recomdate->update($validated);
                }
            }
        }
    }

    public function get_signatories(){
        $employees = User::orderBy('name')->get();
        return response()->json([
            'employees'=>$employees,
        ],200);
    }

    public function delete_item($id){
        $deleted = PRDetails::find($id);
        $deleted->delete();
        if($deleted){
            $deleted_report = PrReportDetails::where('pr_details_id',$id);
            $deleted_report->delete();
        } 
    }

    public function cancel_pr(Request $request, $pr_head_id){
        $update_prhead=PRHead::where('id',$pr_head_id)->first();
        $updatehead['status']='Cancelled';
        $update_prhead->update($updatehead);
        if($update_prhead){
            $update_prdetails=PRDetails::where('pr_head_id',$pr_head_id)->update([
                'status'=>'Cancelled'
            ]);
            if($update_prdetails){
                $prdetails=PRDetails::where('pr_head_id',$pr_head_id)->get();
                foreach($prdetails AS $pd){
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->id)->update([
                        'status'=>'Cancelled'
                    ]);
                }
            }
        }
    }

    public function generate_prno(Request $request){
        $year= ($request->date_prepared!='undefined') ? date("Y", strtotime($request->date_prepared)) : date('Y');
        $year_short = ($request->date_prepared!='undefined') ? date("y", strtotime($request->date_prepared)) : date('y');
        $department_code=Departments::where('id',$request->department)->value('department_code');
        $series_rows = PRSeries::where('year',$year)->count();
        if($series_rows==0){
            $max_series='1';
            $pr_series='0001';
            $pr_no = $department_code.$year_short."-".$pr_series;
        } else {
            $max_series=PRSeries::where('year',$year)->max('series');
            $pr_series=$max_series+1;
            $pr_no = $department_code.$year_short."-".Str::padLeft($pr_series, 4,'000');
        }
        // $series['year']=$year;
        // $series['series']=$pr_series;
        // $pr_series=PRSeries::create($series);
        return $pr_no;
        // $exp=explode('-',$pr_no);
        // if(!PRSeries::where('year',$year)->where('series',$exp[1])->exists()){
        //     // if(!PRSeries::where('year',$year)->where('series',)->exists()){
        //     // if($request->series!=0){
        //         $series['year']=$year;
        //         $series['series']=$pr_series;
        //         $pr_series=PRSeries::create($series);
        //         return $pr_no;
        //     // }
        //     // }
        // }
        
       
        // return response()->json([
        //     'pr_no'=>$pr_no,
        //     'pr_series'=>$pr_series,
        // ],200);
    }

    public function insert_series(Request $request){
        $year= ($request->date!='') ? date("Y", strtotime($request->date)) : date('Y');
        // $pr_no=$request->pr_no;
        // $exp_pr=explode("-",$pr_no);
        // $pr_disp=$exp_pr[1];
       
        // if(!PRSeries::where('year',$year)->exists()){
            $max_series=PRSeries::where('year',$year)->max('series');
            $max=$max_series+1;
            if(!PRSeries::where('year',$year)->where('series',$max)->exists()){
                $series['year']=$year;
                $series['series']=$max;
                $pr_series=PRSeries::create($series);
            }
        // }
    }
    
    public function save_manual(Request $request){
        $item_list=$request->input("item_list");
        $year= ($request->date_prepared!='undefined') ? date("Y", strtotime($request->date_prepared)) : date('Y');
        $year_short = ($request->date_prepared!='undefined') ? date("y", strtotime($request->date_prepared)) : date('y');
        $pr_no=explode('-',$request->pr_no);
        $department_name=Departments::where('id',$request->department)->value('department_name');
        $department_code=Departments::where('id',$request->department)->value('department_code');
        $series_rows = PRSeries::where('year',$year)->count();
        if($series_rows==0){
            $max_series='1';
            $pr_series='0001';
            $pr_no = $department_code.$year_short."-".$pr_series;
        } else {
            $max_series=PRSeries::where('year',$year)->max('series');
            $pr_series=$max_series+1;
            $pr_no = $department_code.$year_short."-".Str::padLeft($pr_series, 4,'000');
        }
        if(!PRSeries::where('year',$year)->where('series',$pr_series)->exists()){
            $series['year']=$year;
            $series['series']=$pr_series;
            $pr_series=PRSeries::create($series);
        }
        // if($pr_series){
            $status=($request->petty_cash==0) ? 'Saved' : 'Closed';
            $data_head=[
                'location'=>$request->location,
                'pr_no'=>$pr_no,
                'site_pr'=>$request->site_pr,
                'date_prepared'=>($request->date_prepared!='undefined') ? $request->date_prepared : null,
                'department_id'=>$request->department,
                'department_name'=>$department_name,
                'urgency'=>$request->urgency,
                'process_code'=>$request->process_code,
                'requestor'=>$request->requestor,
                'enduse'=>$request->enduse,
                'purpose'=>$request->purpose,
                'method'=>'Manual',
                'status'=>$status,
                'petty_cash'=>$request->petty_cash,
            ];    
            $insertprhead=PRHead::create($data_head);
            if($insertprhead && $request->petty_cash==1){
                $data_petty=[
                    'pr_head_id'=>$insertprhead->id,
                    'pr_no'=>$request->pr_no,
                    'prepared_by'=>$request->prepared_by,
                    'recommended_by'=>$request->recommended_by,
                    'approved_by'=>$request->approved_by,
                    'approved_date'=>$request->approved_date,
                    'remarks'=>$request->remarks,
                    'user_id'=>Auth::id(),
                ];  
                PettyCash::create($data_petty);
            }   
            foreach(json_decode($item_list) AS $il){
                $data=[
                    'pr_head_id'=>$insertprhead->id,
                    'quantity'=>$il->qty,
                    'uom'=>$il->uom,
                    'pn_no'=>$il->pn_no,
                    'item_description'=>$il->item_desc,
                    'wh_stocks'=>$il->wh_stocks,
                    'date_needed'=>$il->date_needed,
                    'recom_date'=>$il->recom_date,
                    'status'=>$status,
                ];
                $prdetails_id=PRDetails::create($data);
                if($prdetails_id){
                    $prreport['pr_no']=$insertprhead->pr_no;
                    $prreport['pr_details_id']=$prdetails_id->id;
                    $prreport['item_description']=$il->item_desc;
                    $prreport['pr_qty']=$il->qty;
                    $prreport['uom']=$il->uom;
                    $prreport['status']='pending for RFQ';
                    PrReportDetails::create($prreport);
                }
            }
        // }
    }

    public function save_manual_draft(Request $request){
        $item_list=$request->input("item_list");
        // $department_name=Departments::where('id',$request->department)->value('department_name');
        $year= ($request->date_prepared!='undefined') ? date("Y", strtotime($request->date_prepared)) : date('Y');
        $year_short = ($request->date_prepared!='undefined') ? date("y", strtotime($request->date_prepared)) : date('y');
        $pr_no=explode('-',$request->pr_no);
        $department_name=Departments::where('id',$request->department)->value('department_name');
        $department_code=Departments::where('id',$request->department)->value('department_code');
        $series_rows = PRSeries::where('year',$year)->count();
        $exp=explode('-',$request->pr_no);
        if($series_rows==0){
            $max_series='1';
            $pr_series='0001';
            $pr_no = $department_code.$year_short."-".$pr_series;
        } else {
            $max_series=PRSeries::where('year',$year)->max('series');
            $pr_series=$max_series+1;
            $pr_no = $department_code.$year_short."-".$exp[1];
            // Str::padLeft($pr_series, 4,'000')
        }
        if(!PRSeries::where('year',$year)->where('series',$exp[1])->exists()){
            $series['year']=$year;
            $series['series']=$pr_series;
            $pr_series=PRSeries::create($series);
        }
        $data_head=[
            'location'=>$request->location,
            'pr_no'=>$pr_no,
            'site_pr'=>$request->site_pr,
            'date_prepared'=>$request->date_prepared,
            'department_id'=>$request->department,
            'department_name'=>$department_name,
            'urgency'=>$request->urgency,
            'process_code'=>$request->process_code,
            'requestor'=>$request->requestor,
            'enduse'=>$request->enduse,
            'purpose'=>$request->purpose,
            'method'=>'Manual',
            'status'=>'Draft',
            'petty_cash'=>$request->petty_cash,
        ];    
        $insertprhead=PRHead::create($data_head);
        if($insertprhead && $request->petty_cash==1){
            $data_petty=[
                'pr_head_id'=>$insertprhead->id,
                'pr_no'=>$request->pr_no,
                'prepared_by'=>$request->prepared_by,
                'recommended_by'=>$request->recommended_by,
                'approved_by'=>$request->approved_by,
                'approved_date'=>$request->approved_date,
                'remarks'=>$request->remarks,
                'user_id'=>Auth::id(),
            ];  
            PettyCash::create($data_petty);
        }   
        foreach(json_decode($item_list) AS $il){
            $data=[
                'pr_head_id'=>$insertprhead->id,
                'quantity'=>$il->qty,
                'uom'=>$il->uom,
                'pn_no'=>$il->pn_no,
                'item_description'=>$il->item_desc,
                'wh_stocks'=>$il->wh_stocks,
                'date_needed'=>$il->date_needed,
                'recom_date'=>$il->recom_date,
                'status'=>'Draft',
            ];
            $prdetails_id=PRDetails::create($data);
            if($prdetails_id){
                $prreport['pr_no']=$insertprhead->pr_no;
                $prreport['pr_details_id']=$prdetails_id->id;
                $prreport['item_description']=$il->item_desc;
                $prreport['pr_qty']=$il->qty;
                $prreport['uom']=$il->uom;
                $prreport['status']='pending for RFQ';
                PrReportDetails::create($prreport);
            }
        }
    }

    public function get_allpr(){
        $pr=PRHead::orderBy('pr_no','ASC')->get();
        $prall=[];
        foreach($pr AS $p){
            $prall[]=[
                'id'=>$p->id,
                'status'=>$p->status,
                $p->pr_no,
                $p->date_prepared,
                date('Y-m-d',strtotime($p->created_at)),
                $p->department_name,
                $p->urgency,
                $p->requestor,
                ($p->petty_cash==1) ? 'Yes' : 'No',
                $p->status,
                ''
            ];
        }
        return response()->json([
            'prall'=>$prall,
        ],200);
    }

    public function get_view_details(Request $request, $pr_head_id){
        $prhead=PRHead::where('id',$pr_head_id)->first();
        $prdetails=PRDetails::where('pr_head_id',$pr_head_id)->get();
        $pettycash=PettyCash::where('pr_head_id',$pr_head_id)->get();
        $prepared_by='';
        $recommended_by='';
        $approved_by='';
        foreach($pettycash AS $pc){
            $prepared_by=User::where('id',$pc->prepared_by)->value('name');
            $recommended_by=User::where('id',$pc->recommended_by)->value('name');
            $approved_by=User::where('id',$pc->approved_by)->value('name');
        }
        return response()->json([
            'prhead'=>$prhead,
            'prdetails'=>$prdetails,
            'prepared_by'=>$prepared_by,
            'recommended_by'=>$recommended_by,
            'approved_by'=>$approved_by,
        ],200);
    }

    public function insert_referred(Request $request, $id){
        $insertreferred=PRDetails::where('id',$id)->first();
        $data_details=[
            'referred_date'=>$request->referred_date,
            'referred_reason'=>$request->referred_reason,
            'referred_by'=>Auth::id(),
            'status'=>'Referred',
        ];  
        $insertreferred->update($data_details);  
    }

    public function cancel_prdetails(Request $request, $pr_details_id){
        if(!RfqDetails::where('pr_details_id',$pr_details_id)->exists() && !PoDetails::where('pr_details_id',$pr_details_id)->exists() && !RecomReportDetails::where('pr_details_id',$pr_details_id)->exists()){
            $update_prdetails=PRDetails::where('id',$pr_details_id)->update([
                'status'=>'Cancelled'
            ]);
            if($update_prdetails){
                $update_prreport=PrReportDetails::where('pr_details_id',$pr_details_id)->update([
                    'status'=>'Cancelled'
                ]);
            }
        }else{
            return 'error';
        }
    }

    public function cancel_allpr(Request $request, $pr_head_id){
        if(!RfqHead::where('pr_head_id',$pr_head_id)->exists() && !PrReportDetails::where('po_qty','!=','0')->Orwhere('dpo_qty','!=','0')->Orwhere('rpo_qty','!=','0')->exists() && !PettyCash::where('pr_head_id',$pr_head_id)->exists()){
            $update_prhead=PRHead::where('id',$pr_head_id)->first();
            $updatehead['status']='Cancelled';
            $update_prhead->update($updatehead);
            if($update_prhead){
                $update_prdetails=PRDetails::where('pr_head_id',$pr_head_id)->update([
                    'status'=>'Cancelled'
                ]);
                if($update_prdetails){
                    $prdetails=PRDetails::where('pr_head_id',$pr_head_id)->get();
                    foreach($prdetails AS $pd){
                        $update_prreport=PrReportDetails::where('pr_details_id',$pd->id)->update([
                            'status'=>'Cancelled'
                        ]);
                    }
                }
            }
        }else{
            return 'error';
        }
    }
}