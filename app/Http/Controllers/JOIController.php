<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JORHead;
use App\Models\JORLaborDetails;
use App\Models\JORMaterialDetails;
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
use App\Models\JORFQLaborOffers;
use App\Models\JORFQMaterialOffers;
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
            $joiall[]=[
                'id'=>$j->id,
                'status'=>$j->status,
                'joi_no'=>$j->joi_no,
                'revision_no'=>$j->revision_no,
                date('Y-m-d',strtotime($j->po_date)),
                $j->joi_no,
                $j->vendor_name." (".$identifier.")",
                $j->jor_no,
                ($j->method=='JO') ? 'Job Order Request' : (($j->method=='DPO') ? 'Direct Purchase' : 'Repeat Order'),
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
        return response()->json([
            'total_sum_labor'=>$total_sum_labor,
            'total_sum_pr_labor'=>$total_sum_pr_labor,
        ],200);
    }

    public function check_material_balance($jor_material_details_id,$jo_rfq_material_offer_id){
        $total_sum_material = JOIMaterialDetails::where('jor_material_details_id',$jor_material_details_id)->where('jo_rfq_material_offer_id',$jo_rfq_material_offer_id)->where('status','Saved')->sum('quantity');
        $total_sum_pr_material = JORMaterialDetails::where('id',$jor_material_details_id)->where('status','Saved')->sum('quantity');
        return response()->json([
            'total_sum_material'=>$total_sum_material,
            'total_sum_pr_material'=>$total_sum_pr_material,
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
        // $joi_head_temp = POHeadTemp::where('joi_head_id',$joi_head_id)->first();
        // $joi_terms_temp = POTermsTemp::where('joi_head_id',$joi_head_id)->get();
        // $joi_instruction_temp = POInstructionsTemp::where('joi_head_id',$joi_head_id)->get();
        $joi_dr_array= JOIDr::where('joi_head_id',$joi_head_id)->get();
        $joi_dr= JOIDr::where('joi_head_id',$joi_head_id)->first();
        $joi_dr_labor= JOIDrLabor::where('joi_dr_id',$joi_dr->id)->get();
        $jor_head= JORHead::where('jor_no',$joi_head->jor_no)->first();
        // $po_vendor = VendorDetails::where('id',$joi_head->vendor_details_id)->where('status','=','Active')->first();
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
        // $po_details_temp = PoDetailsTemp::where('joi_head_id',$joi_head_id)->get();
        $cancelled_by=User::where('id',$joi_head->cancelled_by)->value('name');
        $joi_terms = JOITerms::where('joi_head_id',$joi_head_id)->get();
        $joi_instructions = JOIInstructions::where('joi_head_id',$joi_head_id)->get();
        $prepared_by= User::where('id',$joi_head->prepared_by)->value('name');
        $checked_by= User::where('id',$joi_head->checked_by)->value('name');
        $recommended_by= User::where('id',$joi_head->recommended_by)->value('name');
        $approved_by= User::where('id',$joi_head->approved_by)->value('name');
        $total=[];
        foreach($joi_labor_details AS $pd){
            $total[]=$pd->unit_price * $pd->quantity;
        }
        $total_material=[];
        foreach($joi_labor_details AS $jld){
            $total_material[]=$jld->unit_price * $jld->quantity;
        }
        $total_sum=array_sum($total);
        $total_sum_material=array_sum($total_material);
        return response()->json([
            'joi_head_array'=>$joi_head_array,
            'joi_head'=>$joi_head,
            // 'po_head_temp'=>$po_head_temp,
            'joi_dr_array'=>$joi_dr_array,
            'joi_dr'=>$joi_dr,
            'joi_dr_labor'=>$joi_dr_labor,
            'jor_head'=>$jor_head,
            'joi_vendor'=>$joi_vendor,
            'joi_labor_details'=>$joi_labor_details,
            'joi_details_view'=>$joi_details_view,
            'joi_material_details'=>$joi_material_details,
            'joi_material_details_view'=>$joi_material_details_view,
            // 'po_details_temp'=>$po_details_temp,
            'joi_terms'=>$joi_terms,
            // 'po_terms_temp'=>$po_terms_temp,
            'joi_instructions'=>$joi_instructions,
            // 'po_instructions_temp'=>$po_instruction_temp,
            'prepared_by'=>$prepared_by,
            'checked_by'=>$checked_by,
            'recommended_by'=>$recommended_by,
            'approved_by'=>$approved_by,
            'cancelled_by'=>$cancelled_by,
            'grand_labor_total'=>$total_sum,
            'grand_material_total'=>$total_sum_material,
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
                $update_jolabordetails=JOIDrMaterial::where('jor_labor_details_id',$md->jor_labor_details_id)->where('joi_labor_details_id',$md->id)->where('status','!=','Cancelled')->update([
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
}
