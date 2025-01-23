<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JORHead;
use App\Models\JORLaborDetails;
use App\Models\JORMaterialDetails;
use App\Models\JOICoc;
use App\Models\JOICocSeries;
use App\Models\JOIAr;
use App\Models\JOIHead;
use App\Models\JOILaborDetails;
use App\Models\JOIMaterialDetails;
use App\Models\JOISeries;
use App\Models\JOIDrSeries;
use App\Models\JOIDr;
use App\Models\JOIDrLabor;
use App\Models\JOIDrMaterial;
use App\Models\JOIInstructions;
use App\Models\JOITerms;
use App\Models\JOIHeadTemp;
use App\Models\JOILaborDetailsTemp;
use App\Models\JOIMaterialDetailsTemp;
use App\Models\JOIDrLaborTemp;
use App\Models\JOIDrMaterialTemp;
use App\Models\JOIInstructionsTemp;
use App\Models\JOITermsTemp;    
use App\Models\JOIRevisionHead;
use App\Models\JOIRevisionLaborDetails;
use App\Models\JOIRevisionMaterialDetails;
use App\Models\JOIRevisionDr;
use App\Models\JOIRevisionDRLaborlDetails;
use App\Models\JOIRevisionDRMateriallDetails;
use App\Models\JOIRevisionInstructions;
use App\Models\JOIRevisionTerms;    
use App\Models\JORFQLaborOffers;
use App\Models\JORFQMaterialOffers;
use App\Models\JOIRfd;
use App\Models\JOIRfdPayment;
use App\Models\JOIRfdSeries;
use App\Models\VendorDetails;
use App\Models\JOAOQHead;
use App\Models\JORFQTerms;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Config;
class JOIController extends Controller
{
    public function get_alljoi(){
        $joi=JOIHead::orderBy('joi_no','ASC')->get();
        $joiall=[];
        foreach($joi AS $j){
            $identifier=VendorDetails::where('id',$j->vendor_details_id)->value('identifier');
            $site_jor=JORHead::where('jor_no',$j->jor_no)->value('site_jor');
            $project_activity=JORHead::where('jor_no',$j->jor_no)->value('project_activity');
            $joiall[]=[
                'id'=>$j->id,
                'status'=>$j->status,
                'method'=>$j->method,
                'joi_no'=>$j->joi_no,
                'revision_no'=>$j->revision_no,
                ($j->date_prepared!='' || $j->date_prepared!=null) ? date('Y-m-d',strtotime($j->date_prepared)) : '',
                ($j->date_needed!='' || $j->date_needed!=null) ? date('Y-m-d',strtotime($j->date_needed)) : '',
                $j->joi_no,
                $j->site_jor,
                $j->vendor_name." (".$identifier.")",
                $j->project_activity,
                ($j->method=='JO') ? 'Job Order Request' : (($j->method=='DJO') ? 'Direct JO' : 'Repeat JO'),
                ($j->status=='Saved') ? 'JO Issued' : (($j->status=='Draft') ? 'Draft' : 'Cancelled'),
                ''
            ];
        }
        return response()->json([
            'joiall'=>$joiall,
        ],200);
    }
    public function jo_supplier_dropdown(){
        $suppliers = VendorDetails::select('vendor_details.id','identifier','vendor_head.vendor_name')->distinct()->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->join('jo_rfq_vendor', 'vendor_details.id', '=', 'jo_rfq_vendor.vendor_details_id')->join('jo_rfq_labor_offer', 'jo_rfq_vendor.id', '=', 'jo_rfq_labor_offer.jo_rfq_vendor_id')->join('jo_aoq_head', 'jo_rfq_labor_offer.jo_rfq_head_id', '=', 'jo_aoq_head.jo_rfq_head_id')->where('vendor_details.status','=','Active')->where('awarded','=','1')->where('aoq_status','=','Awarded')->get();
        return response()->json([
            'suppliers'=>$suppliers,
        ],200);
    }

    public function get_jorno($vendor_details_id){
        $jorno_dropdown=JORFQLaborOffers::select('jo_rfq_labor_offer.jo_rfq_vendor_id','jo_aoq_head.id','jo_aoq_head.aoq_date','jo_rfq_vendor.jor_no')->distinct()->join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_labor_offer.jo_rfq_vendor_id')->join('jo_aoq_head', 'jo_rfq_labor_offer.jo_rfq_head_id', '=', 'jo_aoq_head.jo_rfq_head_id')->where('jo_rfq_vendor.vendor_details_id',$vendor_details_id)->where('jo_rfq_labor_offer.awarded','=','1')->where('aoq_status','=','Awarded')->get();
        return response()->json([
            'jorno_dropdown'=>$jorno_dropdown,
        ],200);
    }

    public function generate_joi($vendor_details_id,$jor_no){
        $joaoq_head_id_exp=explode('+',$jor_no);
        $jor_no_exp=$joaoq_head_id_exp[0];
        $jo_aoq_head_id=$joaoq_head_id_exp[1];
        $year=date('Y');
        $series_rows = JOISeries::where('year',$year)->count();
        $company=Config::get('constants.company');
        if($series_rows==0){
            $max_series='1';
            $joi_series='0001';
            $joi_no = 'P'.$jor_no_exp."-".$joi_series;
        } else {
            $max_series=JOISeries::where('year',$year)->max('series');
            $joi_series=$max_series+1;
            $joi_no = 'P'.$jor_no_exp."-".Str::padLeft($joi_series, 4,'000');
        }
        
        $jo_dr_series_rows = JOIDrSeries::where('year',$year)->count();
        if($jo_dr_series_rows==0){
            $max_dr_series='1';
            $dr_series='0001';
            $dr_no = $year."-".$dr_series.'-'.$company;
        } else {
            $max_dr_series=JOIDrSeries::where('year',$year)->max('series');
            $dr_series=$max_dr_series+1;
            $dr_no = $year."-".Str::padLeft($dr_series, 4,'000').'-'.$company;
        }
        $joi_head= JOAOQHead::where('id',$jo_aoq_head_id)->where('jor_no',$jor_no_exp)->first();
        $jor_head= JORHead::where('jor_no',$jor_no_exp)->first();
        $vendor= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$vendor_details_id)->where('status','=','Active')->first();
        $joi_labor_details = JORFQLaborOffers::with('jor_labor_details')->select('jo_rfq_labor_offer.id','jo_rfq_labor_offer.jo_rfq_vendor_id', 'jo_rfq_labor_offer.jor_labor_details_id','jo_rfq_labor_offer.offer','jo_rfq_labor_offer.uom','jo_rfq_labor_offer.unit_price','jo_rfq_labor_offer.currency')->join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_labor_offer.jo_rfq_vendor_id')->join('jo_aoq_details', 'jo_rfq_labor_offer.jo_rfq_vendor_id', '=', 'jo_aoq_details.jo_rfq_vendor_id')->join('jo_aoq_head', 'jo_aoq_details.jo_aoq_head_id', '=', 'jo_aoq_head.id')->where('jo_rfq_vendor.vendor_details_id',$vendor_details_id)->where('jo_rfq_labor_offer.jo_rfq_head_id',$joi_head->jo_rfq_head_id)->where('jo_rfq_labor_offer.awarded','=','1')->where('aoq_status','=','Awarded')->get();
        foreach($joi_labor_details AS $jld){
            // $balance = PrReportDetails::where('jo_labor_details_id',$jld->jo_labor_details_id)->where('status','!=','Cancelled')->first();
            // $total_po=($balance->po_qty + $balance->dpo_qty + $balance->rpo_qty);
            // $totals = $balance->pr_qty - $total_po;
            $sum_joi_labor=JOILaborDetails::where('jor_labor_details_id',$jld->jor_labor_details_id)->where('jo_rfq_labor_offer_id',$jld->id)->where('status','Saved')->sum('quantity');
            $deduct = $jld->jor_labor_details->quantity - $sum_joi_labor;
            $total_labor[]=$jld->unit_price * $deduct;
            // $total[]=$jld->unit_price * $balance->pr_qty;
            $jo_rfq_terms=JORFQTerms::where('jo_rfq_vendor_id',$jld->jo_rfq_vendor_id)->get();
        }
        
        $joi_material_details = JORFQMaterialOffers::with('jor_material_details')->select('jo_rfq_material_offer.id','jo_rfq_material_offer.jo_rfq_vendor_id'   , 'jo_rfq_material_offer.jor_material_details_id','jo_rfq_material_offer.offer','jo_rfq_material_offer.uom','jo_rfq_material_offer.unit_price','jo_rfq_material_offer.currency')->join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_material_offer.jo_rfq_vendor_id')->join('jo_aoq_details', 'jo_rfq_material_offer.jo_rfq_vendor_id', '=', 'jo_aoq_details.jo_rfq_vendor_id')->join('jo_aoq_head', 'jo_aoq_details.jo_aoq_head_id', '=', 'jo_aoq_head.id')->where('jo_rfq_vendor.vendor_details_id',$vendor_details_id)->where('jo_rfq_material_offer.jo_rfq_head_id',$joi_head->jo_rfq_head_id)->where('jo_rfq_material_offer.awarded','=','1')->where('aoq_status','=','Awarded')->get();
        foreach($joi_material_details AS $jmd){
            // $balance = PrReportDetails::where('jo_labor_details_id',$jmd->jo_labor_details_id)->where('status','!=','Cancelled')->first();
            // $total_po=($balance->po_qty + $balance->dpo_qty + $balance->rpo_qty);
            // $totals = $balance->pr_qty - $total_po;
            $sum_joi_material=JOIMaterialDetails::where('jor_material_details_id',$jmd->jor_material_details_id)->where('jo_rfq_material_offer_id',$jmd->id)->where('status','Saved')->sum('quantity');
            $deducted = $jmd->jor_material_details->quantity - $sum_joi_material;
            $total_material[]=$jmd->unit_price * $deducted;
            // $total[]=$jmd->unit_price * $balance->pr_qty;
            // $jo_rfq_terms=RFQVendorTerms::where('jo_rfq_vendor_id',$jmd->jo_rfq_vendor_id)->get();
        }
        $total_labor_sum=array_sum($total_labor);
        $total_material_sum=array_sum($total_material);
        return response()->json([
            'vendor'=>$vendor,
            'joi_head'=>$joi_head,
            'jor_head'=>$jor_head,
            'joi_labor_details'=>$joi_labor_details,
            'joi_material_details'=>$joi_material_details,
            'joi_no'=>$joi_no,
            'dr_no'=>$dr_no,
            'jo_rfq_terms'=>$jo_rfq_terms,
            'grand_labor_total'=>$total_labor_sum,
            'grand_material_total'=>$total_material_sum,
            'prepared_by'=>Auth::user()?->name
        ],200);
    }

    public function check_labor_balance($jor_labor_details_id,$jo_rfq_labor_offer_id){
        $total_sum_labor = JOILaborDetails::where('jor_labor_details_id',$jor_labor_details_id)->where('jo_rfq_labor_offer_id',$jo_rfq_labor_offer_id)->where('status','Saved')->sum('quantity');
        $total_sum_pr_labor = JORLaborDetails::where('id',$jor_labor_details_id)->where('status','Saved')->sum('quantity');
        $quantity_labor=JORLaborDetails::where('id',$jor_labor_details_id)->where('status','Saved')->first();
        return response()->json([
            'total_sum_labor'=>$total_sum_labor,
            'total_sum_pr_labor'=>$total_sum_pr_labor,
            'quantity_labor'=>$quantity_labor,
        ],200);
    }

    public function check_material_balance($jor_material_details_id,$jo_rfq_material_offer_id){
        $total_sum_material = JOIMaterialDetails::where('jor_material_details_id',$jor_material_details_id)->where('jo_rfq_material_offer_id',$jo_rfq_material_offer_id)->where('status','Saved')->sum('quantity');
        $total_sum_pr_material = JORMaterialDetails::where('id',$jor_material_details_id)->where('status','Saved')->sum('quantity');
        $quantity_material = JORMaterialDetails::where('id',$jor_material_details_id)->where('status','Saved')->first();
        return response()->json([
            'total_sum_material'=>$total_sum_material,
            'total_sum_pr_material'=>$total_sum_pr_material,
            'quantity_material'=>$quantity_material,
        ],200);
    }

    public function delete_jo_terms($id){
        $deleted = JOITerms::find($id);
        $deleted->delete();
    }

    public function delete_jo_instructions($id){
        $deleted = JOIInstructions::find($id);
        $deleted->delete();
    }

    public function jo_viewdetails($joi_head_id){
        $joi_head_array = JOIHead::where('id',$joi_head_id)->get();
        $joi_head = JOIHead::where('id',$joi_head_id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Cancelled')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->first();
        $joi_ar = JOIAr::where('joi_head_id',$joi_head_id)->first();
        $joi_head_temp = JOIHeadTemp::where('joi_head_id',$joi_head_id)->first();
        $joi_terms_temp = JOITermsTemp::where('joi_head_id',$joi_head_id)->get();
        $joi_instruction_temp = JOIInstructionsTemp::where('joi_head_id',$joi_head_id)->get();
        $joi_dr_array= JOIDr::where('joi_head_id',$joi_head_id)->get();
        $joi_dr= JOIDr::where('joi_head_id',$joi_head_id)->first();
        $joi_dr_labor= JOIDrLabor::where('joi_dr_id',$joi_dr->id)->get();
        $joi_dr_material= JOIDrMaterial::where('joi_dr_id',$joi_dr->id)->get();
        $jor_head= JORHead::where('jor_no',$joi_head->jor_no)->first();
        $joi_vendor= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$joi_head->vendor_details_id)->where('status','=','Active')->first();
        $joi_labor_details = JOILaborDetails::where('joi_head_id',$joi_head_id)->where('quantity','!=','0')->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Cancelled')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $joi_details_view = JOILaborDetails::where('joi_head_id',$joi_head_id)->where('quantity','!=','0')->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $joi_material_details = JOIMaterialDetails::where('joi_head_id',$joi_head_id)->where('quantity','!=','0')->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Cancelled')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $joi_material_details_view = JOIMaterialDetails::where('joi_head_id',$joi_head_id)->where('quantity','!=','0')->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $joi_labor_details_temp = JOILaborDetailsTemp::where('joi_head_id',$joi_head_id)->get();
        $joi_material_details_temp = JOIMaterialDetailsTemp::where('joi_head_id',$joi_head_id)->get();
        $cancelled_by=User::where('id',$joi_head->cancelled_by)->value('name');
        $joi_terms = JOITerms::where('joi_head_id',$joi_head_id)->get();
        $joi_instructions = JOIInstructions::where('joi_head_id',$joi_head_id)->get();
        $prepared_by= User::where('id',$joi_head->prepared_by)->value('name');
        $checked_by= User::where('id',$joi_head->checked_by)->value('name');
        $recommended_by= User::where('id',$joi_head->recommended_by)->value('name');
        $approved_by= User::where('id',$joi_head->approved_by)->value('name');
        $total=[];
        $currency=Config::get('constants.currency');
        foreach($joi_labor_details AS $pd){
            $total[]=$pd->unit_price * $pd->quantity;
        }
        $total_material=[];
        foreach($joi_material_details AS $jmd){
            $total_material[]=$jmd->unit_price * $jmd->quantity;
        }
        $total_sum=array_sum($total);
        $total_sum_material=array_sum($total_material);

        $total_temp=[];
        foreach($joi_labor_details_temp AS $pdt){
            $total_temp[]=$pdt->unit_price * $pdt->quantity;
        }
        $total_material_temp=[];
        foreach($joi_material_details_temp AS $jmdt){
            $total_material_temp[]=$jmdt->unit_price * $jmdt->quantity;
        }
        $total_sum_temp=array_sum($total_temp);
        $total_sum_material_temp=array_sum($total_material_temp);
        return response()->json([
            'joi_head_array'=>$joi_head_array,
            'joi_ar'=>$joi_ar,
            'joi_head'=>$joi_head,
            'joi_head_temp'=>$joi_head_temp,
            'joi_dr_array'=>$joi_dr_array,
            'joi_dr'=>$joi_dr,
            'joi_dr_labor'=>$joi_dr_labor,
            'joi_dr_material'=>$joi_dr_material,
            'jor_head'=>$jor_head,
            'joi_vendor'=>$joi_vendor,
            'joi_labor_details'=>$joi_labor_details,
            'joi_details_view_old'=>$joi_details_view,
            'joi_details_view'=>$joi_details_view,
            'joi_material_details'=>$joi_material_details,
            'joi_material_details_view_old'=>$joi_material_details_view,
            'joi_material_details_view'=>$joi_material_details_view,
            'joi_labor_details_temp'=>$joi_labor_details_temp,
            'joi_material_details_temp'=>$joi_material_details_temp,
            'joi_terms'=>$joi_terms,
            'joi_terms_temp'=>$joi_terms_temp,
            'joi_instructions'=>$joi_instructions,
            'joi_instructions_temp'=>$joi_instruction_temp,
            'prepared_by'=>$prepared_by,
            'checked_by'=>$checked_by,
            'recommended_by'=>$recommended_by,
            'approved_by'=>$approved_by,
            'checked_by_id'=>$joi_head->checked_by,
            'checked_by_id_temp'=>$joi_head_temp->checked_by ?? 0,
            'recommended_by_id'=>$joi_head->recommended_by,
            'recommended_by_id_temp'=>$joi_head_temp->recommended_by ?? 0,
            'approved_by_id'=>$joi_head->approved_by,
            'approved_by_id_temp'=>$joi_head_temp->approved_by ?? 0,
            'cancelled_by'=>$cancelled_by,
            'grand_labor_total'=>$total_sum,
            'grand_material_total'=>$total_sum_material,
            'grand_labor_total_temp'=>$total_sum_temp,
            'grand_material_total_temp'=>$total_sum_material_temp,
            'currency'=>$currency,
        ],200);
    }

    public function save_joi(Request $request){
        $terms_list=$request->input("terms_list");
        $jo_rfq_terms=$request->input("jo_rfq_terms");
        $other_list=$request->input("other_list");
        $joi_labor_details=$request->input("joi_labor_details");
        $joi_material_details=$request->input("joi_material_details");
        $year=date('Y');
        $series_rows = JOISeries::where('year',$year)->count();
        $exp=explode('-',$request->joi_no);
        if($series_rows==0){
            $max_series='1';
            $joi_series='0001';
            $joi_no = 'P'.$request->jor_no."-".$joi_series;
        } else {
            $max_series=JOISeries::where('year',$year)->max('series');
            $joi_series=$max_series+1;
            $joi_no = 'P'.$request->jor_no."-".Str::padLeft($exp[3], 4,'000');
        }
        if(!JOISeries::where('year',$year)->where('series',$exp[3])->exists()){
            $series['year']=$year;
            $series['series']=$joi_series;
            $jo_series=JOISeries::create($series);
        }
        $vendor_name= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$request->vendor_details_id)->where('status','=','Active')->first();
        $data_head['joi_date']=date('Y-m-d');
        $data_head['joi_no']=$joi_no;
        $data_head['jor_no']=$request->jor_no;
        $data_head['date_needed']=$request->date_needed;
        $data_head['completion_work']=$request->completion_work;
        $data_head['date_prepared']=$request->date_prepared;
        $data_head['start_of_work']=$request->start_of_work;
        $data_head['vendor_details_id']=$request->vendor_details_id;
        $data_head['vendor_name']=$vendor_name->vendor_name;
        $data_head['discount']=$request->discount_labor;
        $data_head['discount_material']=$request->discount_material;
        $data_head['vat']=$request->vat;
        $data_head['vat_amount']=$request->vat_amount;
        $data_head['vat_in_ex']=$request->vat_in_ex;
        $data_head['grand_total']= str_replace(',','',$request->grand_total);
        $data_head['prepared_by']=Auth::id();
        $data_head['checked_by']=$request->checked_by;
        $data_head['recommended_by']=$request->recommended_by;
        $data_head['approved_by']=$request->approved_by;
        $data_head['conforme']=$request->conforme;
        $data_head['method']='JO';
        $data_head['status']=$request->status;
        // $data_head['status']='Saved';
        $data_head['user_id']=Auth::id();
        if($request->joi_head_id==0){
            //PO Insert
            $insertjoihead=JOIHead::create($data_head);
            //DR Insert
            $series_dr_rows = JOIDrSeries::where('year',$year)->count();
            $company=Config::get('constants.company');
            $exp_dr=explode('-',$request->dr_no);
            if($series_dr_rows==0){
                $max_dr_series='1';
                $dr_series='0001';
                $dr_no = "JO".$year."-".$dr_series.'-'.$company;
            } else {
                $max_dr_series=JOIDrSeries::where('year',$year)->max('series');
                $dr_series=$max_dr_series+1;
                $dr_no = "JO".$year."-".Str::padLeft($exp_dr[1], 4,'000').'-'.$company;
            }
            if(!JOIDrSeries::where('year',$year)->where('series',$exp_dr[1])->exists()){
                $series['year']=$year;
                $series['series']=$dr_series;
                $po_series=JOIDrSeries::create($series);
            }
            $pr_head_id=JORHead::where('jor_no',$request->jor_no)->value('id');
            $site_jor=JORHead::where('jor_no',$request->jor_no)->value('site_jor');
            $data_dr_head['joi_head_id']=$insertjoihead->id;
            $data_dr_head['jor_head_id']=$pr_head_id;
            $data_dr_head['joi_no']=$joi_no;
            $data_dr_head['jor_no']=$request->jor_no;
            $data_dr_head['site_pr']=$site_jor;
            $data_dr_head['dr_date']=date('Y-m-d');
            $data_dr_head['dr_no']=$dr_no;
            $data_dr_head['series']=$dr_series;
            $data_dr_head['year']=$year;
            $data_dr_head['status']=$request->status;
            $data_dr_head['user_id']=Auth::id();
            $insertdrhead=JOIDr::create($data_dr_head);
            if($request->status=='Saved'){
                $series_ar_rows = JOIAr::where('year',$year)->count();
                if($series_ar_rows==0){
                    $max_ar_series='1';
                    $ar_series='0001';
                    $ar_no = "AR-".$year."-".$ar_series.'-'.$company;
                } else {
                    $max_ar_series=JOIAr::where('year',$year)->max('series');
                    $ar_series=$max_ar_series+1;
                    $ar_no = "AR-".$year."-".Str::padLeft($ar_series, 4,'000').'-'.$company;
                }
                if(!JOIAr::where('year',$year)->where('series',$ar_series)->exists()){
                    $arseries['joi_head_id']=$insertjoihead->id;
                    $arseries['ar_no']=$ar_no;
                    $arseries['ar_date']=$request->date_prepared;
                    $arseries['year']=$year;
                    $arseries['series']=$ar_series;
                    JOIAr::create($arseries);
                }
            }
        }else{
            $insertjoihead=JOIHead::where('id',$request->joi_head_id)->first();
            $insertjoihead->update($data_head);

            $data_dr_head['status']=$request->status;
            $insertdrhead=JOIDr::where('joi_head_id',$request->joi_head_id)->first();
            $insertdrhead->update($data_dr_head);
        }
        
        $x=1;
        $y=0;
        $quantity_labors=array();
        foreach(json_decode($joi_labor_details) AS $pd){
            if(count(json_decode($joi_labor_details))>0){
                $quantity_labor = $request->input("quantity_labor"."$y");
                $quantity_labors[] = $request->input("quantity_labor"."$y");
                if(($request->joi_head_id==0 || $request->joi_head_id!=0) && $request->props_id==0 && $request->status=='Saved'){
                    $data=[
                        'joi_head_id'=>$insertjoihead->id,
                        'jor_labor_details_id'=>$pd->jor_labor_details_id,
                        'jo_rfq_labor_offer_id'=>$pd->id,
                        'item_no'=>$x,
                        // 'item_description'=> ($request->po_head_id==0) ? $pd->offer : (($request->status=='Saved') ? $pd->offer : (($request->props_id!=0) ? $pd->item_description : $pd->offer)),
                        'item_description'=> $pd->offer,
                        'quantity'=>  $quantity_labor,
                        // 'quantity'=>  ($request->po_head_id==0) ? $quantity_labor : (($request->status=='Saved') ? $quantity_labor : (($request->props_id!=0) ? $pd->quantity : $quantity_labor)),
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> $pd->unit_price * $quantity_labor,
                        // 'total_cost'=> ($request->po_head_id==0) ? $pd->unit_price * $quantity_labor : (($request->status=='Saved') ? $pd->unit_price * $quantity_labor : (($request->props_id!=0) ? $pd->unit_price * $pd->quantity : $pd->unit_price * $quantity_labor)),
                        'status'=>$request->status,
                    ];
                }else if(($request->joi_head_id==0 || $request->joi_head_id!=0) && $request->props_id==0 && $request->status=='Draft'){
                    $data=[
                        'joi_head_id'=>$insertjoihead->id,
                        'jor_labor_details_id'=>$pd->jor_labor_details_id,
                        'jo_rfq_labor_offer_id'=>$pd->id,
                        'item_no'=>$x,
                        // 'item_description'=> ($request->po_head_id==0) ? $pd->offer : (($request->status=='Saved') ? $pd->offer : (($request->props_id!=0) ? $pd->item_description : $pd->offer)),
                        'item_description'=> $pd->offer,
                        'quantity'=> $quantity_labor,
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> $pd->unit_price * $quantity_labor,
                        'status'=>$request->status,
                    ];
                }else if($request->props_id!=0 && $request->status=='Saved'){
                    $data=[
                        'joi_head_id'=>$insertjoihead->id,
                        'jor_labor_details_id'=>$pd->jor_labor_details_id,
                        'jo_rfq_labor_offer_id'=>$pd->id,
                        'item_no'=>$x,
                        // 'item_description'=> ($request->po_head_id==0) ? $pd->offer : (($request->status=='Saved') ? $pd->offer : (($request->props_id!=0) ? $pd->item_description : $pd->offer)),
                        'item_description'=> $pd->item_description,
                        'quantity'=> $pd->quantity,
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> $pd->unit_price * $pd->quantity,
                        'status'=>$request->status,
                    ];
                }else if($request->props_id!=0 && $request->status=='Draft'){
                    $data=[
                        'joi_head_id'=>$insertjoihead->id,
                        'jor_labor_details_id'=>$pd->jor_labor_details_id,
                        'jo_rfq_labor_offer_id'=>$pd->id,
                        'item_no'=>$x,
                        // 'item_description'=> ($request->po_head_id==0) ? $pd->offer : (($request->status=='Saved') ? $pd->offer : (($request->props_id!=0) ? $pd->item_description : $pd->offer)),
                        'item_description'=> $pd->item_description,
                        'quantity'=> $pd->quantity,
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> $pd->unit_price * $pd->quantity,
                        'status'=>$request->status,
                    ];
                }
                if($request->joi_head_id==0){
                    if($quantity_labor!=0){
                        $joi_labor_details_id=JOILaborDetails::create($data);
                        if($request->joi_head_id==0 && $request->status=='Saved'){
                            $data_dr_details=[
                                'joi_dr_id'=>$insertdrhead->id,
                                'joi_labor_details_id'=>$joi_labor_details_id->id,
                                'jor_labor_details_id'=>$pd->jor_labor_details_id,
                                'jo_rfq_labor_offer_id'=>$pd->id,
                                'to_deliver'=>$quantity_labor,
                                'quantity'=>$quantity_labor,
                            ];
                        }else if($request->joi_head_id==0 && $request->status=='Draft'){
                            $data_dr_details=[
                                'joi_dr_id'=>$insertdrhead->id,
                                'joi_labor_details_id'=>$joi_labor_details_id->id,
                                'jor_labor_details_id'=>$pd->jor_labor_details_id,
                                'jo_rfq_labor_offer_id'=>$pd->id,
                                'to_deliver'=>$quantity_labor,
                                'quantity'=>$quantity_labor,
                            ];
                        }
                        $joi_dr_items=JOIDrLabor::create($data_dr_details);
                    }
                   
                }else{
                    if($request->props_id==0){
                        if($quantity_labor!=0){
                            $joi_labor_details_id=JOILaborDetails::updateOrCreate(
                                [
                                    'joi_head_id' => $insertjoihead->id,
                                    'jo_rfq_labor_offer_id'=>$pd->id,
                                    'jor_labor_details_id'=>$pd->jor_labor_details_id,
                                ],
                                [
                                    'joi_head_id'=>$insertjoihead->id,
                                    'jor_labor_details_id'=>$pd->jor_labor_details_id,
                                    'jo_rfq_labor_offer_id'=>$pd->id,
                                    'item_no'=>$x,
                                    'item_description'=>$pd->offer,
                                    'quantity'=>$quantity_labor,
                                    'uom'=>$pd->uom,
                                    'unit_price'=>$pd->unit_price,
                                    'currency'=>$pd->currency,
                                    'total_cost'=>$pd->unit_price * $quantity_labor,
                                    'status'=>$request->status,
                                ]
                            );
                            $joi_dr_items=JOIDrLabor::updateOrCreate(
                                [
                                    'joi_labor_details_id'=>$joi_labor_details_id->id,
                                    'jo_rfq_labor_offer_id'=>$pd->id,
                                    'jor_labor_details_id'=>$pd->jor_labor_details_id,
                                ],
                                [
                                    'to_deliver'=>$quantity_labor,
                                    'quantity'=>$quantity_labor,
                                ]
                            );
                        }
                    }else{
                        if($pd->quantity!=0){
                            $joi_labor_details_id=JOILaborDetails::where('id',$pd->id)->update([
                                    'joi_head_id'=>$insertjoihead->id,
                                    'jor_labor_details_id'=>$pd->jor_labor_details_id,
                                    'jo_rfq_labor_offer_id'=>$pd->jo_rfq_labor_offer_id,
                                    'item_no'=>$x,
                                    'item_description'=>$pd->item_description,
                                    'quantity'=>$pd->quantity,
                                    'uom'=>$pd->uom,
                                    'unit_price'=>$pd->unit_price,
                                    'currency'=>$pd->currency,
                                    'total_cost'=> $pd->unit_price * $pd->quantity,
                                    'status'=>$request->status,
                                ]
                            );
                            $joidritems=JOIDrLabor::where('joi_labor_details_id',$pd->id)->where('jor_labor_details_id',$pd->jor_labor_details_id)->where('jo_rfq_labor_offer_id',$pd->jo_rfq_labor_offer_id)->get();
                            foreach($joidritems AS $pdi){
                                $joi_dr_items=JOIDrLabor::where('id',$pdi->id)->update([
                                        'to_deliver'=>$pd->quantity,
                                        'quantity'=>$pd->quantity,
                                    ]
                                );
                            }
                        }
                    }
                }
                $x++;
                $y++;
            }
        }

        $v=1;
        $z=0;
        $quantity_materials=array();
        foreach(json_decode($joi_material_details) AS $jmd){
            if(count(json_decode($joi_material_details))>0){
                $quantity_material = $request->input("quantity_material"."$z");
                $quantity_materials[] = $request->input("quantity_material"."$z");
                if(($request->joi_head_id==0 || $request->joi_head_id!=0) && $request->props_id==0 && $request->status=='Saved'){
                    $data=[
                        'joi_head_id'=>$insertjoihead->id,
                        'jor_material_details_id'=>$jmd->jor_material_details_id,
                        'jo_rfq_material_offer_id'=>$jmd->id,
                        'item_no'=>$v,
                        // 'item_description'=> ($request->po_head_id==0) ? $jmd->offer : (($request->status=='Saved') ? $jmd->offer : (($request->props_id!=0) ? $jmd->item_description : $jmd->offer)),
                        'item_description'=> $jmd->offer,
                        'quantity'=>  $quantity_material,
                        // 'quantity'=>  ($request->po_head_id==0) ? $quantity_material : (($request->status=='Saved') ? $quantity_material : (($request->props_id!=0) ? $jmd->quantity : $quantity_material)),
                        'uom'=>$jmd->uom,
                        'unit_price'=>$jmd->unit_price,
                        'currency'=>$jmd->currency,
                        'total_cost'=> $jmd->unit_price * $quantity_material,
                        // 'total_cost'=> ($request->po_head_id==0) ? $jmd->unit_price * $quantity_material : (($request->status=='Saved') ? $jmd->unit_price * $quantity_material : (($request->props_id!=0) ? $jmd->unit_price * $jmd->quantity : $jmd->unit_price * $quantity_material)),
                        'status'=>$request->status,
                    ];
                }else if(($request->joi_head_id==0 || $request->joi_head_id!=0) && $request->props_id==0 && $request->status=='Draft'){
                    $data=[
                        'joi_head_id'=>$insertjoihead->id,
                        'jor_material_details_id'=>$jmd->jor_material_details_id,
                        'jo_rfq_material_offer_id'=>$jmd->id,
                        'item_no'=>$v,
                        // 'item_description'=> ($request->po_head_id==0) ? $jmd->offer : (($request->status=='Saved') ? $jmd->offer : (($request->props_id!=0) ? $jmd->item_description : $jmd->offer)),
                        'item_description'=> $jmd->offer,
                        'quantity'=> $quantity_material,
                        'uom'=>$jmd->uom,
                        'unit_price'=>$jmd->unit_price,
                        'currency'=>$jmd->currency,
                        'total_cost'=> $jmd->unit_price * $quantity_material,
                        'status'=>$request->status,
                    ];
                }else if($request->props_id!=0 && $request->status=='Saved'){
                    $data=[
                        'joi_head_id'=>$insertjoihead->id,
                        'jor_material_details_id'=>$jmd->jor_material_details_id,
                        'jo_rfq_material_offer_id'=>$jmd->id,
                        'item_no'=>$v,
                        // 'item_description'=> ($request->po_head_id==0) ? $jmd->offer : (($request->status=='Saved') ? $jmd->offer : (($request->props_id!=0) ? $jmd->item_description : $jmd->offer)),
                        'item_description'=> $jmd->item_description,
                        'quantity'=> $jmd->quantity,
                        'uom'=>$jmd->uom,
                        'unit_price'=>$jmd->unit_price,
                        'currency'=>$jmd->currency,
                        'total_cost'=> $jmd->unit_price * $jmd->quantity,
                        'status'=>$request->status,
                    ];
                }else if($request->props_id!=0 && $request->status=='Draft'){
                    $data=[
                        'joi_head_id'=>$insertjoihead->id,
                        'jor_material_details_id'=>$jmd->jor_material_details_id,
                        'jo_rfq_material_offer_id'=>$jmd->id,
                        'item_no'=>$v,
                        // 'item_description'=> ($request->po_head_id==0) ? $jmd->offer : (($request->status=='Saved') ? $jmd->offer : (($request->props_id!=0) ? $jmd->item_description : $jmd->offer)),
                        'item_description'=> $jmd->item_description,
                        'quantity'=> $jmd->quantity,
                        'uom'=>$jmd->uom,
                        'unit_price'=>$jmd->unit_price,
                        'currency'=>$jmd->currency,
                        'total_cost'=> $jmd->unit_price * $jmd->quantity,
                        'status'=>$request->status,
                    ];
                }
                if($request->joi_head_id==0){
                    if($quantity_material!=0){
                        $joi_material_details_id=JOIMaterialDetails::create($data);
                        if($request->joi_head_id==0 && $request->status=='Saved'){
                            $data_dr_details=[
                                'joi_dr_id'=>$insertdrhead->id,
                                'joi_material_details_id'=>$joi_material_details_id->id,
                                'jor_material_details_id'=>$jmd->jor_material_details_id,
                                'jo_rfq_material_offer_id'=>$jmd->id,
                                'to_deliver'=>$quantity_material,
                                'quantity'=>$quantity_material,
                            ];
                        }else if($request->joi_head_id==0 && $request->status=='Draft'){
                            $data_dr_details=[
                                'joi_dr_id'=>$insertdrhead->id,
                                'joi_material_details_id'=>$joi_material_details_id->id,
                                'jor_material_details_id'=>$jmd->jor_material_details_id,
                                'jo_rfq_material_offer_id'=>$jmd->id,
                                'to_deliver'=>$quantity_material,
                                'quantity'=>$quantity_material,
                            ];
                        }
                        $joi_dr_items=JOIDrMaterial::create($data_dr_details);
                    }
                }else{
                    if($request->props_id==0){
                        if($quantity_material!=0){
                            $joi_material_details_id=JOIMaterialDetails::updateOrCreate(
                                [
                                    'joi_head_id' => $insertjoihead->id,
                                    'jo_rfq_material_offer_id'=>$jmd->id,
                                    'jor_material_details_id'=>$jmd->jor_material_details_id,
                                ],
                                [
                                    'joi_head_id'=>$insertjoihead->id,
                                    'jor_material_details_id'=>$jmd->jor_material_details_id,
                                    'jo_rfq_material_offer_id'=>$jmd->id,
                                    'item_no'=>$v,
                                    'item_description'=>$jmd->offer,
                                    'quantity'=>$quantity_material,
                                    'uom'=>$jmd->uom,
                                    'unit_price'=>$jmd->unit_price,
                                    'currency'=>$jmd->currency,
                                    'total_cost'=>$jmd->unit_price * $quantity_material,
                                    'status'=>$request->status,
                                ]
                            );
                            $joi_dr_items=JOIDrMaterial::updateOrCreate(
                                [
                                    'joi_material_details_id'=>$joi_material_details_id->id,
                                    'jo_rfq_material_offer_id'=>$jmd->id,
                                    'jor_material_details_id'=>$jmd->jor_material_details_id,
                                ],
                                [
                                    'to_deliver'=>$quantity_material,
                                    'quantity'=>$quantity_material,
                                ]
                            );
                        }
                    }else{
                        if($jmd->quantity!=0){
                            $joi_material_details_id=JOIMaterialDetails::where('id',$jmd->id)->update([
                                    'joi_head_id'=>$insertjoihead->id,
                                    'jor_material_details_id'=>$jmd->jor_material_details_id,
                                    'jo_rfq_material_offer_id'=>$jmd->jo_rfq_material_offer_id,
                                    'item_no'=>$v,
                                    'item_description'=>$jmd->item_description,
                                    'quantity'=>$jmd->quantity,
                                    'uom'=>$jmd->uom,
                                    'unit_price'=>$jmd->unit_price,
                                    'currency'=>$jmd->currency,
                                    'total_cost'=> $jmd->unit_price * $jmd->quantity,
                                    'status'=>$request->status,
                                ]
                            );
                            $joidritems=JOIDrMaterial::where('jor_material_details_id',$jmd->id)->where('jor_material_details_id',$jmd->jor_material_details_id)->where('jo_rfq_material_offer_id',$jmd->jo_rfq_material_offer_id)->get();
                            foreach($joidritems AS $jmdi){
                                $joi_dr_items=JOIDrMaterial::where('id',$jmdi->id)->update([
                                        'to_deliver'=>$jmd->quantity,
                                        'quantity'=>$jmd->quantity,
                                    ]
                                );
                            }
                        }
                    }
                }
                $v++;
                $z++;
            }
        }

        foreach(json_decode($jo_rfq_terms) AS $rt){
            if(count(json_decode($jo_rfq_terms))>0){
                if($request->joi_head_id==0){
                    $terms = JOITerms::where('joi_head_id',$insertjoihead->id)->where('terms', $rt->terms)->first();
                    if(is_null($terms)) {
                        $terms = new JOITerms([
                            'joi_head_id' => $insertjoihead->id,
                            'terms' => $rt->terms
                        ]);
                        $terms->save();
                    }else{
                        $terms->terms = $rt->terms;
                        $terms->update();
                    }
                }else{
                    $terms = JOITerms::where('id',$rt->id)->first();
                    $terms->terms = $rt->terms;
                    $terms->update();
                }
            }
        }
        
        foreach(json_decode($terms_list) AS $il){
            if(count(json_decode($terms_list))>0){
                $terms = JOITerms::where('joi_head_id',$insertjoihead->id)->where('terms', $il->terms_condition)->first();
                if(is_null($terms)) {
                    $terms = new JOITerms([
                        'joi_head_id' => $insertjoihead->id,
                        'terms' => $il->terms_condition
                    ]);
                    $terms->save();
                }else{
                    $terms->terms = $il->terms_condition;
                    $terms->update();
                }
            }
        }
       
        
        foreach(json_decode($other_list) AS $ol){
            if(count(json_decode($other_list))>0){
                if($request->joi_head_id==0){
                    $data_instructions=[
                        'joi_head_id'=>$insertjoihead->id,
                        'instructions'=>$ol->instructions
                    ];
                    if(!JOIInstructions::where('joi_head_id',$insertjoihead->id)->where('instructions',$ol->instructions)->exists()){
                        $otherinstructions=JOIInstructions::create($data_instructions);
                    }else{
                        $otherinstructions=JOIInstructions::where('joi_head_id',$insertjoihead->id)->where('instructions',$ol->instructions)->update($data_instructions);
                    }
                }else{
                    if($ol->id==0){
                        $data_instructions=[
                            'joi_head_id'=>$insertjoihead->id,
                            'instructions'=>$ol->instructions
                        ];
                        $otherinstructions=JOIInstructions::create($data_instructions);
                    }else{
                        $otherinstructions = JOIInstructions::where('id',$ol->id)->first();
                        $otherinstructions->instructions = $ol->instructions;
                        $otherinstructions->update();
                    }
                }
            }
        }
        echo $insertjoihead->id;
    }

    public function cancel_all_jo(Request $request, $joi_head_id){
        $jolabordetails=JOILaborDetails::where('joi_head_id',$joi_head_id)->where('status','!=','Cancelled')->get();
        $jomaterialdetails=JOIMaterialDetails::where('joi_head_id',$joi_head_id)->where('status','!=','Cancelled')->get();
        $update_head=JOIHead::where('id',$joi_head_id)->update([
            'status'=>'Cancelled',
            'cancelled_by'=>Auth::id(),
            'cancelled_date'=>date('Y-m-d h:i:s'),
            'cancelled_reason'=>$request->cancel_all_reason
        ]);
        $update_head=JOIDr::where('joi_head_id',$joi_head_id)->update([
            'status'=>'Cancelled',
            'cancelled_by'=>Auth::id(),
            'cancelled_date'=>date('Y-m-d h:i:s'),
            'cancelled_reason'=>$request->cancel_all_reason
        ]);
        $update_jolabordetails=JOILaborDetails::where('joi_head_id',$joi_head_id)->where('status','!=','Cancelled')->update([
            'status'=>'Cancelled',
            'cancelled_by'=>Auth::id(),
            'cancelled_date'=>date('Y-m-d h:i:s'),
            'cancelled_reason'=>$request->cancel_all_reason
        ]);

        $update_jomaterialdetails=JOIMaterialDetails::where('joi_head_id',$joi_head_id)->where('status','!=','Cancelled')->update([
            'status'=>'Cancelled',
            'cancelled_by'=>Auth::id(),
            'cancelled_date'=>date('Y-m-d h:i:s'),
            'cancelled_reason'=>$request->cancel_all_reason
        ]);
        if($update_jomaterialdetails){
            foreach($jolabordetails AS $pd){
                $update_jolabordetails=JOIDrLabor::where('jor_labor_details_id',$pd->jor_labor_details_id)->where('joi_labor_details_id',$pd->id)->where('status','!=','Cancelled')->update([
                    'status'=>'Cancelled',
                ]);
            }
            foreach($jomaterialdetails AS $md){
                $update_jomaterialdetails=JOIDrMaterial::where('jor_material_details_id',$md->jor_material_details_id)->where('joi_material_details_id',$md->id)->where('status','!=','Cancelled')->update([
                    'status'=>'Cancelled',
                ]);
            }
        }
    }

    public function cancel_jo_items(Request $request, $joi_labor_details_id){
        $update_joi_labordetails=JOILaborDetails::where('id',$joi_labor_details_id)->update([
            'status'=>'Cancelled',
            'cancelled_by'=>Auth::id(),
            'cancelled_date'=>date('Y-m-d h:i:s'),
            'cancelled_reason'=>$request->cancel_reason
        ]);
        if($update_joi_labordetails){
            $update_joi_dr_labordetails=JOIDrLabor::where('joi_labor_details_id',$joi_labor_details_id)->update([
                'status'=>'Cancelled',
            ]);
        }
    }

    public function cancel_jo_material_items(Request $request, $joi_material_details_id){
        $update_joi_labordetails=JOIMaterialDetails::where('id',$joi_material_details_id)->update([
            'status'=>'Cancelled',
            'cancelled_by'=>Auth::id(),
            'cancelled_date'=>date('Y-m-d h:i:s'),
            'cancelled_reason'=>$request->cancel_reason
        ]);
        if($update_joi_labordetails){
            $update_joi_dr_labordetails=JOIDrMaterial::where('joi_material_details_id',$joi_material_details_id)->update([
                'status'=>'Cancelled',
            ]);
        }
    }

    public function save_change_joi(Request $request){
        $joi_dr=$request->input("joi_dr");
        $joi_dr_labor=$request->input("joi_dr_labor");
        $joi_dr_material=$request->input("joi_dr_material");
        $terms_list=$request->input("terms_list");
        $joi_terms=$request->input("joi_terms");
        $joi_instructions=$request->input("joi_instructions");
        $other_list=$request->input("other_list");
        $joi_labor_details=$request->input("joi_labor_details");
        $joi_material_details=$request->input("joi_material_details");
        $data_head=[
            'joi_head_id'=>$request->props_id,
            'discount'=>$request->discount_labor,
            'discount_material'=>$request->discount_material,
            'vat'=>$request->vat,
            'vat_amount'=>$request->vat_amount,
            'vat_in_ex'=>$request->vat_in_ex,
            'grand_total'=>$request->grand_total,
            'revision_no'=>0,
            'checked_by'=>$request->checked_by,
            'recommended_by'=>$request->recommended_by,
            'approved_by'=>$request->approved_by,
        ];
        $joihead_temp=JOIHeadTemp::create($data_head);
        $y=0;
        foreach(json_decode($joi_labor_details) AS $jld){
            $quantity = $request->input("quantity_labor"."$y");
            $data_details=[
                'joi_labor_details_id'=>$jld->id,
                'joi_head_id'=>$jld->joi_head_id,
                'jor_labor_details_id'=>$jld->jor_labor_details_id,
                'jo_rfq_labor_offer_id'=>$jld->jo_rfq_labor_offer_id,
                'item_description'=>$jld->item_description,
                'uom'=>$jld->uom,
                'unit_price'=>$jld->unit_price,
                'currency'=>$jld->currency,
                'quantity'=>$quantity,
                'total_cost'=>$jld->unit_price * $quantity,
            ];
            $joilabordetails_temp=JOILaborDetailsTemp::create($data_details);
            $y++;
        }

        $z=0;
        foreach(json_decode($joi_material_details) AS $jld){
            $quantity = $request->input("quantity_material"."$z");
            $data_material_details=[
                'joi_material_details_id'=>$jld->id,
                'joi_head_id'=>$jld->joi_head_id,
                'jor_material_details_id'=>$jld->jor_material_details_id,
                'jo_rfq_material_offer_id'=>$jld->jo_rfq_material_offer_id,
                'item_description'=>$jld->item_description,
                'uom'=>$jld->uom,
                'unit_price'=>$jld->unit_price,
                'currency'=>$jld->currency,
                'quantity'=>$quantity,
                'total_cost'=>$jld->unit_price * $quantity,
            ];
            $joilabordetails_temp=JOIMaterialDetailsTemp::create($data_material_details);
            $z++;
        }

        if(count(json_decode($joi_terms))>0){
            foreach(json_decode($joi_terms) AS $jt){
                
                $data_terms=[
                    'joi_terms_id' => $jt->id,
                    'joi_head_id'=>$request->props_id,
                    'terms'=>$jt->terms,
                ];
                $joi_terms_temp=JOITermsTemp::create($data_terms);
            }
        }

        if(count(json_decode($terms_list))>0){
            foreach(json_decode($terms_list) AS $il){
           
                $data_terms1=[
                    'joi_terms_id' => '0',
                    'joi_head_id' => $request->props_id,
                    'terms' => $il->terms_condition
                ];
                $joi_terms_temp1=JOITermsTemp::create($data_terms1);
            }
        }

        if(count(json_decode($joi_instructions))>0){
            foreach(json_decode($joi_instructions) AS $pi){
                $data_ins=[
                    'joi_instruction_id'=>$pi->id,
                    'joi_head_id'=>$request->props_id,
                    'instructions'=>$pi->instructions,
                ];
                $joi_ins_temp=JOIInstructionsTemp::create($data_ins);
            }
        }

        if(count(json_decode($other_list))>0){
            foreach(json_decode($other_list) AS $ol){
                $data_ins1=[
                    'joi_instruction_id'=>'0',
                    'joi_head_id' => $request->props_id,
                    'instructions' => $ol->instructions
                ];
                $joi_ins_temp1=JOIInstructionsTemp::create($data_ins1);
            }
        }

        $data_head=JOIHead::where('id',$request->props_id)->update([
            'status'=>'Revised',
        ]);
    }

    public function save_joi_approved_revision(Request $request){
        $joi_head=$request->input("joi_head");
        $joi_dr=$request->input("joi_dr");
        $joi_dr_labor=$request->input("joi_dr_labor");
        $joi_dr_material=$request->input("joi_dr_material");
        $terms_list=$request->input("terms_list");
        $joi_terms=$request->input("joi_terms");
        $joi_instructions=$request->input("joi_instructions");
        $other_list=$request->input("other_list");
        $joi_labor_details=$request->input("joi_labor_details");
        $joi_material_details=$request->input("joi_material_details");
        $revision_max=JOIHead::where('id',$request->props_id)->max('revision_no');
        $revision_no=$revision_max+1;
        foreach(json_decode($joi_head) AS $jh){
            $data_head=[
                'joi_head_id'=>$jh->id,
                'jor_no'=>$jh->jor_no,
                'vendor_details_id'=>$jh->vendor_details_id,
                'vendor_name'=>$jh->vendor_name,
                'joi_no'=>$jh->joi_no,
                'joi_date'=>$jh->joi_date,
                'discount'=>$jh->discount,
                'discount_material'=>$jh->discount_material,
                'vat'=>$jh->vat,
                'vat_amount'=>$jh->vat_amount,
                'vat_in_ex'=>$jh->vat_in_ex,
                'grand_total'=>$jh->grand_total,
                'prepared_by'=>$jh->prepared_by,
                'checked_by'=>$jh->checked_by,
                'recommended_by'=>$jh->recommended_by,
                'approved_by'=>$jh->approved_by,
                'conforme'=>$jh->conforme,
                'user_id'=>$jh->user_id,
                'method'=>$jh->method,
                'revision_no'=>$jh->revision_no ?? 0,
                'status'=>$jh->status,
                'cancelled_date'=>$jh->cancelled_date,
                'cancelled_by'=>$jh->cancelled_by,
                'cancelled_reason'=>$jh->cancelled_reason,
            ];
            $joi_revision_head=JOIRevisionHead::create($data_head);
        }

        foreach(json_decode($joi_labor_details) AS $jd){
            $data_details=[
                'joi_head_rev_id'=>$joi_revision_head->id,
                'joi_head_id'=>$jd->joi_head_id,
                'item_no'=>$jd->item_no,
                'jor_labor_details_id'=>$jd->jor_labor_details_id,
                'jo_rfq_labor_offer_id'=>$jd->jo_rfq_labor_offer_id,
                'reference_joi_labor_details_id'=>$jd->reference_joi_labor_details_id,
                'reference_joi_no'=>$jd->reference_joi_no,
                'item_description'=>$jd->item_description,
                'quantity'=>$jd->quantity,
                'uom'=>$jd->uom,
                'unit_price'=>$jd->unit_price,
                'total_cost'=>$jd->total_cost,
                'currency'=>$jd->currency,
                'cancelled_date'=>$jd->cancelled_date,
                'cancelled_by'=>$jd->cancelled_by,
                'cancelled_reason'=>$jd->cancelled_reason,
                'status'=>$jd->status,
            ];
            $joi_revision_details=JOIRevisionLaborDetails::create($data_details);
        }

        foreach(json_decode($joi_material_details) AS $jd){
            $data_material_details=[
                'joi_head_rev_id'=>$joi_revision_head->id,
                'joi_head_id'=>$jd->joi_head_id,
                'item_no'=>$jd->item_no,
                'jor_material_details_id'=>$jd->jor_material_details_id,
                'jo_rfq_material_offer_id'=>$jd->jo_rfq_material_offer_id,
                'reference_joi_material_details_id'=>$jd->reference_joi_material_details_id,
                'reference_joi_no'=>$jd->reference_joi_no,
                'item_description'=>$jd->item_description,
                'quantity'=>$jd->quantity,
                'uom'=>$jd->uom,
                'unit_price'=>$jd->unit_price,
                'total_cost'=>$jd->total_cost,
                'currency'=>$jd->currency,
                'cancelled_date'=>$jd->cancelled_date,
                'cancelled_by'=>$jd->cancelled_by,
                'cancelled_reason'=>$jd->cancelled_reason,
                'status'=>$jd->status,
            ];
            $joi_material_revision_details=JOIRevisionMaterialDetails::create($data_material_details);
        }

        foreach(json_decode($joi_dr) AS $jdr){
            $data_dr=[
                'joi_head_rev_id'=>$joi_revision_head->id,
                'joi_dr_id'=>$jdr->id,
                'joi_head_id'=>$jdr->joi_head_id,
                'jor_head_id'=>$jdr->jor_head_id,
                'joi_no'=>$jdr->joi_no,
                'jor_no'=>$jdr->jor_no,
                'site_pr'=>$jdr->site_pr,
                'dr_date'=>$jdr->dr_date,
                'dr_no'=>$jdr->dr_no,
                'status'=>$jdr->status,
                'delivery_date'=>$jdr->delivery_date,
                'user_id'=>$jdr->user_id,
                'cancelled_date'=>$jdr->cancelled_date,
                'cancelled_by'=>$jdr->cancelled_by,
                'cancelled_reason'=>$jdr->cancelled_reason,
                'revision_no'=>$jdr->revision_no
            ];
            $joi_revision_dr=JOIRevisionDr::create($data_dr);
        }

        foreach(json_decode($joi_dr_labor) AS $jdri){
            $data_drlabor=[
                'joi_head_rev_id'=>$joi_revision_head->id,
                'joi_dr_rev_id'=>$joi_revision_dr->id,
                'joi_dr_id'=>$jdri->joi_dr_id,
                'joi_labor_details_id'=>$jdri->joi_labor_details_id,
                'jor_labor_details_id'=>$jdri->jor_labor_details_id,
                'jo_rfq_labor_offer_id'=>$jdri->jo_rfq_labor_offer_id,
                'quantity'=>$jdri->quantity,
                'status'=>$jdri->status
            ];
            $joi_revision_drlabor=JOIRevisionDRLaborlDetails::create($data_drlabor);
        }
        foreach(json_decode($joi_dr_material) AS $jdrm){
            $data_drmaterial=[
                'joi_head_rev_id'=>$joi_revision_head->id,
                'joi_dr_rev_id'=>$joi_revision_dr->id,
                'joi_dr_id'=>$jdrm->joi_dr_id,
                'joi_material_details_id'=>$jdrm->joi_material_details_id,
                'jor_material_details_id'=>$jdrm->jor_material_details_id,
                'jo_rfq_material_offer_id'=>$jdrm->jo_rfq_material_offer_id,
                'quantity'=>$jdrm->quantity,
                'status'=>$jdrm->status
            ];
            $joi_revision_drmaterial=JOIRevisionDRMateriallDetails::create($data_drmaterial);
        }
        foreach(json_decode($joi_terms) AS $jt){
            $data_terms=[
                'joi_head_rev_id'=>$joi_revision_head->id,
                'joi_head_id'=>$jt->joi_head_id,
                'terms'=>$jt->terms
            ];
            $po_revision_terms=JOIRevisionTerms::create($data_terms);
        }
        foreach(json_decode($joi_instructions) AS $pi){
            $data_instructions=[
                'joi_head_rev_id'=>$joi_revision_head->id,
                'joi_head_id'=>$pi->joi_head_id,
                'instructions'=>$pi->instructions
            ];
            $joi_revision_instructions=JOIRevisionInstructions::create($data_instructions);
        }

        if($joi_revision_head){
            $joi_head_temp=JOIHeadTemp::where('joi_head_id',$request->props_id)->first();
            $data_head=JOIHead::where('id',$request->props_id)->update([
                'rev_approved_by'=>$request->approved_by_rev,
                'rev_approved_date'=>$request->approved_date,
                'rev_approved_reason'=>$request->approved_reason,
                'discount'=>$joi_head_temp->discount,
                'discount_material'=>$joi_head_temp->discount_material,
                'vat'=>$joi_head_temp->vat,
                'vat_amount'=>$joi_head_temp->vat_amount,
                'vat_in_ex'=>$joi_head_temp->vat_in_ex,
                'grand_total'=>$joi_head_temp->grand_total,
                'checked_by'=>$joi_head_temp->checked_by,
                'recommended_by'=>$joi_head_temp->recommended_by,
                'approved_by'=>$joi_head_temp->approved_by,
                'revision_no'=>$revision_no,
                'status'=>'Saved',
            ]);
            
            foreach(json_decode($joi_dr) AS $jdr){
                $data_dr=JOIDr::where('id',$jdr->id)->update([
                    'revision_no'=>$revision_no,
                ]);
            }
            $y=0;
            $joi_details_temp=JOILaborDetailsTemp::where('joi_head_id',$request->props_id)->get();
            foreach($joi_details_temp AS $jld){
                $data_details=JOILaborDetails::where('id',$jld->joi_labor_details_id)->update([
                    'quantity'=>$jld->quantity,
                    'item_description'=>$jld->item_description,
                    'uom'=>$jld->uom,
                    'unit_price'=>$jld->unit_price,
                    'currency'=>$jld->currency,
                    'total_cost'=>$jld->total_cost,
                ]);
                $data_dr_details=JOIDrLabor::where('joi_labor_details_id',$jld->joi_labor_details_id)->where('jor_labor_details_id',$jld->jor_labor_details_id)->where('jo_rfq_labor_offer_id',$jld->jo_rfq_labor_offer_id)->update([
                    'quantity'=>$jld->quantity,
                ]);   
                $y++;
            }
            $y=0;
            $joi_material_details_temp=JOIMaterialDetailsTemp::where('joi_head_id',$request->props_id)->get();
            foreach($joi_material_details_temp AS $jmd){
                $data_details=JOIMaterialDetails::where('id',$jmd->joi_material_details_id)->update([
                    'quantity'=>$jmd->quantity,
                    'item_description'=>$jmd->item_description,
                    'uom'=>$jmd->uom,
                    'unit_price'=>$jmd->unit_price,
                    'currency'=>$jmd->currency,
                    'total_cost'=>$jmd->total_cost,
                ]);
                $data_dr_details=JOIDrMaterial::where('joi_material_details_id',$jmd->joi_material_details_id)->where('jor_material_details_id',$jmd->jor_material_details_id)->where('jo_rfq_material_offer_id',$jmd->jo_rfq_material_offer_id)->update([
                    'quantity'=>$jmd->quantity,
                ]);   
                $y++;
            }
            $joi_terms_tempd=JOITermsTemp::where('joi_head_id',$request->props_id)->get();
            foreach($joi_terms_tempd AS $jtt){
                if($jtt->joi_terms_id!=0){
                    $update_terms=JOITerms::where('id',$jtt->joi_terms_id)->update([
                        'terms'=>$jtt->terms
                    ]);
                }else{
                    $terms = new JOITerms([
                        'joi_head_id' => $jtt->joi_head_id,
                        'terms' => $jtt->terms
                    ]);
                    $terms->save();
                }
            }
            $joi_instruction_tempd=JOIInstructionsTemp::where('joi_head_id',$request->props_id)->get();
            foreach($joi_instruction_tempd AS $pid){
                if($pid->joi_instruction_id!=0){
                    $update_instructions=JOIInstructions::where('id',$pid->joi_instruction_id)->update([
                        'instructions'=>$pid->instructions
                    ]);
                }else{
                    $others = new JOIInstructions([
                        'joi_head_id' => $pid->joi_head_id,
                        'instructions' => $pid->instructions
                    ]);
                    $others->save();
                }
            }

            $deletedhead = JOIHeadTemp::where('joi_head_id',$request->props_id);
            $deletedhead->delete();
            $deleteddetails = JOILaborDetailsTemp::where('joi_head_id',$request->props_id);
            $deleteddetails->delete();
            $deletedmaterialdetails = JOIMaterialDetailsTemp::where('joi_head_id',$request->props_id);
            $deletedmaterialdetails->delete();
            $deletedterms = JOITermsTemp::where('joi_head_id',$request->props_id);
            $deletedterms->delete();
            $deletedins = JOIInstructionsTemp::where('joi_head_id',$request->props_id);
            $deletedins->delete();
        }
    }

    public function old_jo_revision_data($joi_head_rev_id){
        $joi_head_rev = JOIRevisionHead::where('joi_head_id',$joi_head_rev_id)->get();
        return response()->json([
            'joi_head_rev'=>$joi_head_rev,
        ],200);
    }

    public function view_jo_revision_data($joi_head_rev_id){
        $joi_head = JOIRevisionHead::where('id',$joi_head_rev_id)->first();
        $joi_head_rev = JOIRevisionHead::where('id',$joi_head_rev_id)->get();
        $joi_labor_details_rev = JOIRevisionLaborDetails::where('joi_head_rev_id',$joi_head_rev_id)->get();
        $joi_material_details_rev = JOIRevisionMaterialDetails::where('joi_head_rev_id',$joi_head_rev_id)->get();
        $joi_dr_rev = JOIRevisionDr::where('joi_head_rev_id',$joi_head_rev_id)->first();
        $joi_drlabor_rev = JOIRevisionDRLaborlDetails::where('joi_head_rev_id',$joi_head_rev_id)->get();
        $joi_drmaterial_rev = JOIRevisionDrMateriallDetails::where('joi_head_rev_id',$joi_head_rev_id)->get();
        $joi_terms_rev = JOIRevisionTerms::where('joi_head_rev_id',$joi_head_rev_id)->get();
        $joi_instructions_rev = JOIRevisionInstructions::where('joi_head_rev_id',$joi_head_rev_id)->get();
        $jor_head= JORHead::where('jor_no',$joi_head->jor_no)->first();
        $joi_vendor= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$joi_head->vendor_details_id)->where('status','=','Active')->first();
        $cancelled_by=User::where('id',$joi_head->cancelled_by)->value('name');
        $prepared_by= User::where('id',$joi_head->prepared_by)->value('name');
        $checked_by= User::where('id',$joi_head->checked_by)->value('name');
        $recommended_by= User::where('id',$joi_head->recommended_by)->value('name');
        $approved_by= User::where('id',$joi_head->approved_by)->value('name');
        return response()->json([
            'joi_head_rev'=>$joi_head,
            'jor_head'=>$jor_head,
            'joi_vendor'=>$joi_vendor,
            'joi_labor_details_rev'=>$joi_labor_details_rev,
            'joi_material_details_rev'=>$joi_material_details_rev,
            'joi_dr_rev'=>$joi_dr_rev,
            'joi_drlabor_rev'=>$joi_drlabor_rev,
            'joi_drmaterial_rev'=>$joi_drmaterial_rev,
            'joi_terms_rev'=>$joi_terms_rev,
            'joi_instructions_rev'=>$joi_instructions_rev,
            'prepared_by'=>$prepared_by,
            'checked_by'=>$checked_by,
            'recommended_by'=>$recommended_by,
            'approved_by'=>$approved_by,
            'cancelled_by'=>$cancelled_by,
        ],200);
    }

    public function joi_dropdown(){
        $joi_dropdown = JOIDr::select('joi_head_id','joi_no','revision_no')->distinct()->where('status','Saved')->get();
        return response()->json([
            'joi_dropdown'=>$joi_dropdown,
        ],200);
    }

    public function generate_jo_dr($joi_head_id){
        $year=date('Y');
        $company=Config::get('constants.company');
        $dr_series_rows = JOIDrSeries::where('year',$year)->count();
        if($dr_series_rows==0){
            $max_dr_series='1';
            $dr_series='0001';
            $dr_no = "JO".$year."-".$dr_series.'-'.$company;
        } else {
            $max_dr_series=JOIDrSeries::where('year',$year)->max('series');
            $dr_series=$max_dr_series+1;
            $dr_no = "JO".$year."-".Str::padLeft($dr_series, 4,'000').'-'.$company;
        }
        $joi_dr = JOIDr::where('joi_head_id',$joi_head_id)->orderBy('id','DESC')->orderBy('joi_head_id','ASC')->first();
        $joi_dr_distinct = JOIDr::select('joi_head_id','jor_head_id','joi_no','jor_no','site_pr','revision_no')->distinct()->where('joi_head_id',$joi_head_id)->get();
        $count_joi_head_id = JOIDr::where('joi_head_id',$joi_head_id)->count();
        $purpose=JORHead::where('id',$joi_dr->jor_head_id)->value('purpose');
        $requestor=JORHead::where('id',$joi_dr->jor_head_id)->value('requestor');
        $project_activity=JORHead::where('id',$joi_dr->jor_head_id)->value('project_activity');
        $general_description=JORHead::where('id',$joi_dr->jor_head_id)->value('general_description');
        $joi_head = JOIHead::where('id',$joi_head_id)->first();
        $prepared_by=Auth::user()?->name;
        $vendor=VendorDetails::select('vendor_details.id','identifier','vendor_name')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$joi_head->vendor_details_id)->where('status','=','Active')->first();
        $joi_dr_labor=JOIDrLabor::where('joi_dr_id',$joi_dr->id)->where('to_deliver','!=',0)->where('received_qty','>=','quantity')->get();
        $joi_dr_material=JOIDrMaterial::where('joi_dr_id',$joi_dr->id)->where('to_deliver','!=',0)->where('received_qty','>=','quantity')->get();
        $total_delivered=[];
        foreach($joi_dr_labor AS $pdi){
            $delivered_qty=JOIDrLabor::where('jor_labor_details_id',$pdi->jor_labor_details_id)->where('jo_rfq_labor_offer_id',$pdi->jo_rfq_labor_offer_id)->value('delivered_qty');
            $total_delivered[]=$delivered_qty;
        }
        $total_delivered_material=[];
        foreach($joi_dr_material AS $jmdi){
            $deliveredm_qty=JOIDrMaterial::where('jor_material_details_id',$jmdi->jor_material_details_id)->where('jo_rfq_material_offer_id',$jmdi->jo_rfq_material_offer_id)->value('delivered_qty');
            $total_delivered_material[]=$deliveredm_qty;
        }
        $total_sumdelivered=array_sum($total_delivered);
        $total_material_sumdelivered=array_sum($total_delivered_material);
        return response()->json([
            'dr_no'=>$dr_no,
            'joi_dr'=>$joi_dr,
            'joi_dr_mult'=>$joi_dr_distinct,
            'count_joi_head_id'=>$count_joi_head_id,
            'joi_dr_labor'=>$joi_dr_labor,
            'joi_dr_material'=>$joi_dr_material,
            'purpose'=>$purpose,
            'requestor'=>$requestor,
            'project_activity'=>$project_activity,
            'general_description'=>$general_description,
            'joi_vendor'=>$vendor,
            'prepared_by'=>$prepared_by,
            'total_sumdelivered'=>$total_delivered,
            'total_material_sumdelivered'=>$total_delivered_material,
        ],200);
    }

    public function get_offer_labor($jo_rfq_labor_offer_id){
        $offer = JORFQLaborOffers::where('id',$jo_rfq_labor_offer_id)->where('awarded','1')->first();
        return response()->json([
            'offer'=>$offer,
        ],200);
    }

    public function get_offer_material($jo_rfq_material_offer_id){
        $offer = JORFQMaterialOffers::where('id',$jo_rfq_material_offer_id)->where('awarded','1')->first();
        return response()->json([
            'offer'=>$offer,
        ],200);
    }

    public function check_jo_labor_dr_balance($joi_dr_id,$joi_labor_details_id){
        $balance = JOIDrLabor::where('joi_dr_id',$joi_dr_id)->where('joi_labor_details_id',$joi_labor_details_id)->first();
        $balance_delivered = JOIDrLabor::where('joi_labor_details_id',$joi_labor_details_id)->sum('received_qty');
        // $balance_delivered = JOIDrLabor::where('joi_labor_details_id',$joi_labor_details_id)->sum('delivered_qty');
        return response()->json([
            'balance'=>$balance->quantity - $balance_delivered,
            'delivered_qty'=>$balance->delivered_qty,
        ],200);
    }

    public function check_jo_material_dr_balance($joi_dr_id,$joi_material_details_id){
        $balance = JOIDrMaterial::where('joi_dr_id',$joi_dr_id)->where('joi_material_details_id',$joi_material_details_id)->first();
        $balance_delivered = JOIDrMaterial::where('joi_material_details_id',$joi_material_details_id)->sum('received_qty');
        // $balance_delivered = JOIDrMaterial::where('joi_material_details_id',$joi_material_details_id)->sum('delivered_qty');
        return response()->json([
            'balance'=>$balance->quantity - $balance_delivered,
            'delivered_qty'=>$balance->delivered_qty,
        ],200);
    }

    public function check_remaining_dr_labor_balance($joi_labor_details_id){
        $balance_sum = JOIDrLabor::where('joi_labor_details_id',$joi_labor_details_id)->sum('received_qty');
        return response()->json([
            'balance_sum'=>$balance_sum,
        ],200);
    }

    public function check_remaining_dr_material_balance($joi_material_details_id){
        $balance_sum = JOIDrMaterial::where('joi_material_details_id',$joi_material_details_id)->sum('received_qty');
        return response()->json([
            'balance_sum'=>$balance_sum,
        ],200);
    }

    public function save_jo_dr(Request $request){
        if($request->identifier==0){
            $y=0;
            $updatedrhead=JOIDr::where('id',$request->joi_dr_id)->first();
            $data_dr_head['delivery_date']=date('Y-m-d');
            $data_dr_head['driver']=$request->driver;
            $data_dr_head['identifier']='1';
            $data_dr_head['print_identifier']='1';
            $updatedrhead->update($data_dr_head);
            foreach(json_decode($request->joi_dr_labor) AS $jdl){
                $to_deliver_labor = $request->input("to_deliver_labor"."$y");
                $remaining_labor_delivery = $request->input("remaining_labor_qty"."$y");
                $joi_dr_labor_details=JOIDrLabor::where('id',$jdl->id)->update([
                    'delivered_qty'=>$to_deliver_labor,
                    // 'to_deliver'=>$remaining_labor_delivery - $to_deliver_labor,
                ]);
                $y++;
            }
            $z=0;
            foreach(json_decode($request->joi_dr_material) AS $jmd){
                $to_deliver_material = $request->input("to_deliver_material"."$z");
                $remaining_material_delivery = $request->input("remaining_material_qty"."$z");
                $joi_dr_material_details=JOIDrMaterial::where('id',$jmd->id)->update([
                    'delivered_qty'=>$to_deliver_material,
                    // 'to_deliver'=>$remaining_material_delivery - $to_deliver_material,
                ]);
                $z++;
            }
            echo $updatedrhead->id;
        }else{
            $year=date('Y');
            $series_dr_rows = JOIDrSeries::where('year',$year)->count();
            $company=Config::get('constants.company');
            $exp_dr=explode('-',$request->dr_no);
            if($series_dr_rows==0){
                $max_dr_series='1';
                $dr_series='0001';
                $dr_no = "JO".$year."-".$dr_series.'-'.$company;
            } else {
                $max_dr_series=JOIDrSeries::where('year',$year)->max('series');
                $dr_series=$max_dr_series+1;
                $dr_no = "JO".$year."-".Str::padLeft($exp_dr[1], 4,'000').'-'.$company;
            }
            if(!JOIDrSeries::where('year',$year)->where('series',$exp_dr[1])->exists()){
                $series['year']=$year;
                $series['series']=$dr_series;
                $po_series=JOIDrSeries::create($series);
            }
            foreach(json_decode($request->joi_dr) AS $joidr){
                $joi_dr['joi_head_id']=$joidr->joi_head_id;
                $joi_dr['jor_head_id']=$joidr->jor_head_id;
                $joi_dr['joi_no']=$joidr->joi_no;
                $joi_dr['jor_no']=$joidr->jor_no;
                $joi_dr['site_pr']=$joidr->site_pr;
                $joi_dr['dr_date']=date('Y-m-d');
                $joi_dr['dr_no']=$dr_no;
                $joi_dr['status']='Saved';
                $joi_dr['delivery_date']=date('Y-m-d');
                $joi_dr['driver']=$request->driver;
                $joi_dr['revision_no']=$joidr->revision_no;
                $joi_dr['user_id']=Auth::id();
                $joi_dr['print_identifier']='1';
                $joi_dr['identifier']='1';
                $joi_drinsert=JOIDr::create($joi_dr);
                $y=0;
                foreach(json_decode($request->joi_dr_labor) AS $jdl){
                    $to_deliver_labor = $request->input("to_deliver_labor"."$y");
                    $remaining_labor_delivery = $request->input("remaining_labor_qty"."$y");
                    if($to_deliver_labor!=0){
                        $joi_dr_laborins['joi_dr_id']=$joi_drinsert->id;
                        $joi_dr_laborins['joi_labor_details_id']=$jdl->joi_labor_details_id;
                        $joi_dr_laborins['jor_labor_details_id']=$jdl->jor_labor_details_id;
                        $joi_dr_laborins['jo_rfq_labor_offer_id']=$jdl->jo_rfq_labor_offer_id;
                        $joi_dr_laborins['quantity']=$jdl->quantity;
                        // $joi_dr_laborins['to_deliver']=$remaining_labor_delivery - $to_deliver_labor;
                        $joi_dr_laborins['to_deliver']=$jdl->to_deliver;
                        $joi_dr_laborins['delivered_qty']=$to_deliver_labor;
                        $joi_drinsertitem=JOIDrLabor::create($joi_dr_laborins);
                    }
                    $y++;
                }
                $z=0;
                foreach(json_decode($request->joi_dr_material) AS $jdm){
                    $to_deliver_material = $request->input("to_deliver_material"."$z");
                    $remaining_material_delivery = $request->input("remaining_material_qty"."$z");
                    if($to_deliver_material!=0){
                        $joi_dr_materialins['joi_dr_id']=$joi_drinsert->id;
                        $joi_dr_materialins['joi_material_details_id']=$jdm->joi_material_details_id;
                        $joi_dr_materialins['jor_material_details_id']=$jdm->jor_material_details_id;
                        $joi_dr_materialins['jo_rfq_material_offer_id']=$jdm->jo_rfq_material_offer_id;
                        $joi_dr_materialins['quantity']=$jdm->quantity;
                        // $joi_dr_materialins['to_deliver']=$remaining_material_delivery - $to_deliver_material;
                        $joi_dr_materialins['to_deliver']=$jdm->to_deliver;
                        $joi_dr_materialins['delivered_qty']=$to_deliver_material;
                        $joi_drinsertmaterial=JOIDrMaterial::create($joi_dr_materialins);
                    }
                    $z++;
                }
                echo $joi_drinsert->id;
            }
        }
    }

    public function save_jo_received_dr(Request $request){
        $y=0;
        $updatedrhead=JOIDr::where('id',$request->joi_dr_id)->first();
        $data_dr_head['driver']=$request->driver;
        $data_dr_head['received']='1';
        $updatedrhead->update($data_dr_head);
        foreach(json_decode($request->joi_dr_labor) AS $jdl){
            $received_qty = $request->input("received_qty"."$y");
            $remaining_labor_delivery = $request->input("remaining_labor_qty"."$y");
            $joi_dr_labor_details=JOIDrLabor::where('id',$jdl->id)->update([
                'received_qty'=>$received_qty,
                'to_deliver'=>$remaining_labor_delivery - $received_qty,
            ]);
            $y++;
        }
        $z=0;
        foreach(json_decode($request->joi_dr_material) AS $jmd){
            $received_material_qty = $request->input("received_material_qty"."$z");
            $remaining_material_delivery = $request->input("remaining_material_qty"."$z");
            $joi_dr_material_details=JOIDrMaterial::where('id',$jmd->id)->update([
                'received_qty'=>$received_material_qty,
                'to_deliver'=>$remaining_material_delivery - $received_material_qty,
            ]);
            $z++;
        }
        echo $updatedrhead->id;
    }

    public function get_jo_alldr(){
        $dr=JOIDr::orderBy('dr_no','ASC')->get();
        $drall=[];
        foreach($dr AS $d){
            $method=JOIHead::where('id',$d->joi_head_id)->value('method');
            $drall[]=[
                'id'=>$d->id,
                "<center>".date('F d,Y',strtotime($d->dr_date))."</center>",
                "<center>".$d->dr_no."</center>",
                "<center>".$d->joi_no.(($d->revision_no!=0) ? '.r'.$d->revision_no : '')."</center>",
                ($method=='JO') ? '<center>Job Order Request</center>' : (($method=='DJO') ? '<center>Direct Job Order</center>' : '<center>Repeat Job Order</center>'),
                ''
            ];
        }
        return response()->json([
            'drall'=>$drall,
        ],200);
    }

    public function get_jo_dr_view($joi_dr_id){
        $joi_dr = JOIDr::where('id',$joi_dr_id)->where('status','Saved')->first();
        $joi_dr_labor = JOIDrLabor::where('joi_dr_id',$joi_dr_id)->get();
        $joi_dr_material = JOIDrMaterial::where('joi_dr_id',$joi_dr_id)->get();
        $purpose=JORHead::where('id',$joi_dr->jor_head_id)->value('purpose');
        $requestor=JORHead::where('id',$joi_dr->jor_head_id)->value('requestor');
        $project_activity=JORHead::where('id',$joi_dr->jor_head_id)->value('project_activity');
        $general_description=JORHead::where('id',$joi_dr->jor_head_id)->value('general_description');
        $joi_head = JOIHead::where('id',$joi_dr->joi_head_id)->first();
        $prepared_by=User::where('id',$joi_dr->user_id)->value('name');
        $joi_vendor=VendorDetails::select('vendor_details.id','identifier','vendor_name')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$joi_head->vendor_details_id)->where('status','=','Active')->first();
        return response()->json([
            'joi_dr'=>$joi_dr,
            'joi_dr_labor'=>$joi_dr_labor,
            'joi_dr_material'=>$joi_dr_material,
            'purpose'=>$purpose,
            'requestor'=>$requestor,
            'project_activity'=>$project_activity,
            'general_description'=>$general_description,
            'prepared_by'=>$prepared_by,
            'joi_vendor'=>$joi_vendor,
        ],200);
    }

    public function get_rfd_joi_dropdown(){
        $rfd_joi_dropdown = JOIHead::select('id','joi_no','revision_no')->distinct()->where('status','Saved')->get();
        return response()->json([
            'rfd_joi_dropdown'=>$rfd_joi_dropdown,
        ],200);
    }

    public function generate_rfd_joi($joi_head_id){
        $year=date('Y');
        $company=Config::get('constants.company');
        $rfd_series_rows = JOIRfdSeries::where('year',$year)->count();
        if($rfd_series_rows==0){
            $max_rfd_series='1';
            $rfd_series='0001';
            $rfd_no = 'JRFD'.$year."-".$rfd_series.'-'.$company;
        } else {
            $max_rfd_series=JOIRfdSeries::where('year',$year)->max('series');
            $rfd_series=$max_rfd_series+1;
            $rfd_no = 'JRFD'.$year."-".Str::padLeft($rfd_series, 4,'000').'-'.$company;
        }
        $joi_head= JOIHead::where('id',$joi_head_id)->where('status','Saved')->first();
        $vendor= VendorDetails::where('id',$joi_head->vendor_details_id)->where('status','Active')->first();
        $jor_head= JORHead::where('jor_no',$joi_head->jor_no)->first();
        $joi_labor = JOILaborDetails::where('joi_head_id',$joi_head_id)->where('status','Saved')->get();
        $joi_material = JOIMaterialDetails::where('joi_head_id',$joi_head_id)->where('status','Saved')->get();
        $rfd_head = JOIRfd::where('joi_head_id',$joi_head_id)->where('status','Saved')->first();
        $rfd_payments = JOIRfdPayment::with('joi_rfd')->whereHas('joi_rfd', function ($joirfd) {
            $joirfd->where('status','Saved')->Orwhere('status','Draft');
        })->where('joi_id',$joi_head_id)->get();
        $total_labor=[];
        foreach($joi_labor AS $jl){
            $total_labor[]=$jl->unit_price * $jl->quantity;
        }
        $total_material=[];
        foreach($joi_material AS $jm){
            $total_material[]=$jm->unit_price * $jm->quantity;
        }
        $total_sum_labor=array_sum($total_labor);
        $total_sum_material=array_sum($total_material);

        $total_payments=0;
        foreach($rfd_payments AS $rp){
            $total_payments+=$rp->payment_amount;
        }
        return response()->json([
            'joi_head'=>$joi_head,
            'jor_head'=>$jor_head,
            'joi_labor'=>$joi_labor,
            'joi_material'=>$joi_material,
            'vendor'=>$vendor,
            'rfd_head'=>$rfd_head,
            'rfd_no'=>$rfd_no,
            'rfd_payments'=>$rfd_payments,
            'total_sum_labor'=>$total_sum_labor,
            'total_sum_material'=>$total_sum_material,
            'total_payments'=>$total_payments,
            'prepared_by'=>Auth::user()?->name
        ],200);
    }

    public function joi_rfd_viewdetails($joi_head_id){
        $year=date('Y');
        $company=Config::get('constants.company');
        $rfd_series_rows = JOIRfdSeries::where('year',$year)->count();
        if($rfd_series_rows==0){
            $max_rfd_series='1';
            $rfd_series='0001';
            $rfd_no = 'JRFD'.$year."-".$rfd_series.'-'.$company;
        } else {
            $max_rfd_series=JOIRfdSeries::where('year',$year)->max('series');
            $rfd_series=$max_rfd_series+1;
            $rfd_no = 'JRFD'.$year."-".Str::padLeft($rfd_series, 4,'000').'-'.$company;
        }
        $joi_head= JOIHead::where('id',$joi_head_id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Cancelled')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->first();
        $vendor= VendorDetails::where('id',$joi_head->vendor_details_id)->where('status','Active')->first();
        $jor_head= JORHead::where('jor_no',$joi_head->jor_no)->first();
        $joi_labor = JOILaborDetails::where('joi_head_id',$joi_head_id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $joi_material = JOIMaterialDetails::where('joi_head_id',$joi_head_id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $rfd_head = JOIRfd::where('joi_head_id',$joi_head_id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Draft');
        })->orderByDesc('id')->first();
        $rfd_payments = JOIRfdPayment::with('joi_rfd')->whereHas('joi_rfd', function ($porfd) {
            $porfd->where('status','Saved')->Orwhere('status','Draft');
        })->where('joi_id',$joi_head_id)->get();
        $total_labor=[];
        foreach($joi_labor AS $jl){
            $total_labor[]=$jl->unit_price * $jl->quantity;
        }
        $total_material=[];
        foreach($joi_material AS $jm){
            $total_material[]=$jm->unit_price * $jm->quantity;
        }
        $total_sum_labor=array_sum($total_labor);
        $total_sum_material=array_sum($total_material);

        $total_payments=0;
        foreach($rfd_payments AS $rp){
            $total_payments+=$rp->payment_amount;
        }
        return response()->json([
            'joi_head'=>$joi_head,
            'jor_head'=>$jor_head,
            'joi_labor'=>$joi_labor,
            'joi_material'=>$joi_material,
            'rfd_head'=>$rfd_head,
            'rfd_no'=>$rfd_no,
            'rfd_payments'=>$rfd_payments,
            'vendor'=>$vendor,
            'total_sum_labor'=>$total_sum_labor,
            'total_sum_material'=>$total_sum_material,
            'total_payments'=>$total_payments,
            'prepared_by'=>Auth::user()?->name
        ],200);
    }

    public function save_joi_rfd(Request $request){
        $payment_list=$request->input("payment_list");
        $year=date('Y');
        $company=Config::get('constants.company');
        $series_rows = JOIRfdSeries::where('year',$year)->count();
        $exp=explode('-',$request->rfd_no);
        if($series_rows==0){
            $max_series='1';
            $rfd_series='0001';
            $rfd_no = 'JRFD'.$year."-".$rfd_series.'-'.$company;
        } else {
            $max_series=JOIRfdSeries::where('year',$year)->max('series');
            $rfd_series=$max_series+1;
            $rfd_no = 'JRFD'.$year."-".Str::padLeft($exp[1], 4,'000').'-'.$company;
        }
        if(!JOIRfdSeries::where('year',$year)->where('series',$exp[1])->exists()){
            $series['year']=$year;
            $series['series']=$rfd_series;
            $rfd_series_ins=JOIRfdSeries::create($series);
        }
        $checked_by_name= User::where('id',$request->checked_by)->value('name');
        $noted_by_name= User::where('id',$request->noted_by)->value('name');
        $endorsed_by_name= User::where('id',$request->endorsed_by)->value('name');
        $approved_by_name= User::where('id',$request->approved_by)->value('name');
        $received_by_name= User::where('id',$request->received_by)->value('name');
        $rfd_count = JOIRfd::where('joi_head_id',$request->joi_head_id)->count();
        if($rfd_count==0){
            $identifier='1';
        } else {
            $max = JOIRfd::where('joi_head_id',$request->joi_head_id)->max('identifier');
            $identifier= $max+1;
        }
        $data_rfd_head['joi_head_id']=$request->joi_head_id;
        $data_rfd_head['joi_no']=$request->joi_no;
        $data_rfd_head['jor_no']=$request->jor_no;
        $data_rfd_head['rfd_date']=$request->rfd_date ?? '';
        $data_rfd_head['rfd_no']=$rfd_no;
        $data_rfd_head['due_date']=$request->due_date ?? '';
        $data_rfd_head['check_due']=$request->check_due ?? '';
        $data_rfd_head['check_name']=$request->check_name ?? '';
        $data_rfd_head['company']=$request->company ?? '';
        $data_rfd_head['bank_no']=$request->bank_no ?? '';
        $data_rfd_head['pay_to']=$request->pay_to ?? '';
        $data_rfd_head['mode']=$request->mode;
        $data_rfd_head['notes']=$request->notes;
        $data_rfd_head['identifier']= $identifier;
        $data_rfd_head['grand_total']=$request->grand_total;
        $data_rfd_head['sub_total']=$request->subtotal;
        $data_rfd_head['balance']=$request->balance;
        $data_rfd_head['checked_by']=$request->checked_by;
        $data_rfd_head['checked_by_name']=$checked_by_name;
        $data_rfd_head['noted_by']=$request->noted_by;
        $data_rfd_head['noted_by_name']=$noted_by_name;
        $data_rfd_head['endorsed_by']=$request->endorsed_by;
        $data_rfd_head['endorsed_by_name']=$endorsed_by_name;
        $data_rfd_head['approved_by']=$request->approved_by;
        $data_rfd_head['approved_by_name']=$approved_by_name;
        $data_rfd_head['received_by']=$request->received_by;
        $data_rfd_head['received_by_name']=$received_by_name;
        $data_rfd_head['status']=$request->status;
        $data_rfd_head['user_id']=Auth::id();
        if($request->rfd_id==0){
            $insertrfdhead=JOIRfd::create($data_rfd_head);
        }else{
            $insertrfdhead=JOIRfd::where('id',$request->rfd_id)->first();
            $insertrfdhead->update($data_rfd_head);
        }
        foreach(json_decode($payment_list) AS $pl){
            if(count(json_decode($payment_list))>0){
                $payments = JOIRfdPayment::where('joi_id',$request->joi_head_id)->where('payment_description', $pl->payment_description)->first();
                if(is_null($payments)) {
                    $data_payments=[
                        'joi_rfd_id'=>$insertrfdhead->id,
                        'joi_id'=>$request->joi_head_id,
                        'rfd_date'=>$request->rfd_date,
                        'payment_description'=>$pl->payment_description,
                        'payment_details'=>$pl->payment_details,
                        'payment_amount'=> $pl->payment_amount,
                        'ewt_vat'=> $pl->ewt_vat,
                        'ewt_percent'=> $pl->ewt_percent,
                        'ewt_amount'=> $pl->ewt_amount,
                        'retention_percent'=> $pl->retention_percent,
                        'retention_amount'=> $pl->retention_amount,
                        'user_id'=> Auth::id(),
                    ];
                    $rfd_payment_id=JOIRfdPayment::create($data_payments);
                }
            }
        }
        echo $insertrfdhead->id;
    }

    public function delete_joi_payment($id){
        $deleted = JOIRfdPayment::find($id);
        $deleted->delete();
    }

    public function cancel_all_joi_rfd(Request $request, $joi_head_id){
        $update_head=JOIRfd::where('joi_head_id',$joi_head_id)->where('status','!=','Cancelled')->update([
            'status'=>'Cancelled',
            'cancelled_by'=>Auth::id(),
            'cancelled_date'=>date('Y-m-d h:i:s'),
            'cancelled_reason'=>$request->cancel_all_reason
        ]);
    }

    public function get_alljoirfd(){
        $rfd=JOIRfd::orderBy('rfd_no','ASC')->get();
        $rfdall=[];
        foreach($rfd AS $r){
            $rfd_payment = JOIRfdPayment::where('joi_rfd_id',$r->id)->get();
            $total_due=0;
            foreach($rfd_payment AS $rp){
                $total_due += $rp->payment_amount - $rp->ewt_amount - $rp->retention_amount;
            }
            $rfdall[]=[
                'id'=>$r->id,
                'joi_head_id'=>$r->joi_head_id,
                'status'=>$r->status,
                ($r->rfd_date!=null && $r->rfd_date!='' && $r->rfd_date!='null') ? date('Y-m-d',strtotime($r->rfd_date)) : '',
                $r->company,
                $r->pay_to,
                $r->rfd_no,
                number_format($total_due,4),
                ($r->mode==1) ? 'Check' : 'Cash',
                '',
                ''
            ];
        }
        return response()->json([
            'rfdall'=>$rfdall,
        ],200);
    }

    public function rfd_joi_displayview($rfd_id){
        $rfd_head = JOIRfd::where('id',$rfd_id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Draft')->Orwhere('status','Cancelled');
        })->orderBy('id')->first();
        $identifier_loop=[];
        for($x=1;$x<=$rfd_head->identifier;$x++){
            $identifier_loop[]=$x;
        }
        $rfd_payments = JOIRfdPayment::with('joi_rfd')->where('joi_id',$rfd_head->joi_head_id)->whereHas('joi_rfd', function ($joi_rfd) use ($identifier_loop) {
            $joi_rfd->whereIn('identifier',$identifier_loop);
        })->get();
        // $rfd_payments = JOIRfdPayment::with('joi_rfd')->where('joi_rfd_id',$rfd_head->id)->get();
        $joi_head= JOIHead::where('id',$rfd_head->joi_head_id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Cancelled')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->first();
        $vendor= VendorDetails::where('id',$joi_head->vendor_details_id)->where('status','Active')->first();
        $jor_head= JORHead::where('jor_no',$joi_head->jor_no)->first();

        $joi_labor = JOILaborDetails::where('joi_head_id',$joi_head->id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $joi_material = JOIMaterialDetails::where('joi_head_id',$joi_head->id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $prepared_by=User::where('id',$rfd_head->user_id)->value('name');
        return response()->json([
            'joi_head'=>$joi_head,
            'jor_head'=>$jor_head,
            'joi_labor'=>$joi_labor,
            'joi_material'=>$joi_material,
            'vendor'=>$vendor,
            'rfd_head'=>$rfd_head,
            'rfd_payments'=>$rfd_payments,
            'prepared_by'=>$prepared_by,
        ],200);
    }

    public function rfd_jo_data($id){
        $rfd_head = JOIRfd::where('joi_head_id',$id)->get();
        return response()->json([
            'rfd_head'=>$rfd_head,
        ],200);
    }

    public function get_cocdata($id){
        $year=date('Y');
        $company=Config::get('constants.company');
        $series_coc_rows = JOICocSeries::where('year',$year)->count();
        if($series_coc_rows==0){
            $max_coc_series='1';
            $coc_series='0001';
            $coc_no = "COC-".$year."-".$coc_series.'-'.$company;
        } else {
            $max_coc_series=JOICocSeries::where('year',$year)->max('series');
            $coc_series=$max_coc_series+1;
            $coc_no = "COC-".$year."-".Str::padLeft($coc_series, 4,'000').'-'.$company;
        }
        $joi_coc = JOICoc::where('joi_head_id',$id)->first();
        $joi_head = JOIHead::where('id',$id)->first();
        $jor_head= JORHead::where('jor_no',$joi_head->jor_no)->first();
        $joi_labors = JOILaborDetails::where('joi_head_id',$id)->get();
        $joi_materials = JOIMaterialDetails::where('joi_head_id',$id)->get();
        $vendor= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$joi_head->vendor_details_id)->where('status','=','Active')->first();
        return response()->json([
            'coc_no'=>$coc_no,
            'joi_coc'=>$joi_coc,
            'joi_head'=>$joi_head,
            'jor_head'=>$jor_head,
            'joi_labors'=>$joi_labors,
            'joi_materials'=>$joi_materials,
            'vendor'=>$vendor,
        ],200);
    }

    public function get_checkposition($checked_by){
        $position=User::where('id',$checked_by)->value('position');
        return response()->json([
            'position'=>$position,
        ],200);
    }

    public function get_approveposition($approved_by){
        $position=User::where('id',$approved_by)->value('position');
        return response()->json([
            'position'=>$position,
        ],200);
    }

    public function save_coc(Request $request){
        $year=date('Y');
        $company=Config::get('constants.company');
        $exp=explode('-',$request->coc_no);
        $series_coc_rows = JOICocSeries::where('year',$year)->count();
        if($series_coc_rows==0){
            $max_coc_series='1';
            $coc_series='0001';
            $coc_no = "COC-".$year."-".$coc_series.'-'.$company;
        } else {
            $max_coc_series=JOICocSeries::where('year',$year)->max('series');
            $coc_series=$max_coc_series+1;
            $coc_no = "COC-".$year."-".Str::padLeft($exp[2], 4,'000').'-'.$company;
        }
        if(!JOICocSeries::where('year',$year)->where('series',$exp[2])->exists()){
            $arseries['year']=$year;
            $arseries['series']=$coc_series;
            JOICocSeries::create($arseries);
        }

        $data_coc['joi_head_id']=$request->joi_head_id;
        $data_coc['coc_no']=$coc_no;
        $data_coc['date_prepared']=$request->date_prepared;
        $data_coc['warranty']=$request->warranty;
        $data_coc['approved_by']=$request->approved_by;
        $data_coc['checked_by']=$request->checked_by;
        $data_coc['saved']=1;
        $data_coc['user_id']=Auth::id();
        $insertrfdhead=JOICoc::create($data_coc);
    }
}
