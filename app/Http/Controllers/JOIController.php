<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JORHead;
use App\Models\JOIHead;
use App\Models\JOILaborDetails;
use App\Models\JOIMaterialDetails;
use App\Models\JOISeries;
use App\Models\JOIDrSeries;
use App\Models\JOIDr;
use App\Models\JOIDrLabor;
use App\Models\JOIDrMaterial;
use App\Models\JOIInstruction;
use App\Models\JOITerms;
use App\Models\JORFQLaborOffers;
use App\Models\JORFQMaterialOffers;
use App\Models\VendorDetails;
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
        $joi_labor_details = JORFQLaborOffers::select('jo_rfq_offers.id','jo_rfq_offers.jo_rfq_vendor_id', 'remaining_qty', 'jo_rfq_offers.jo_labor_details_id','jo_rfq_offers.offer','jo_rfq_offers.uom','jo_rfq_offers.unit_price','jo_rfq_offers.currency')->join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_offers.jo_rfq_vendor_id')->join('aoq_details', 'jo_rfq_offers.jo_rfq_vendor_id', '=', 'aoq_details.jo_rfq_vendor_id')->join('aoq_head', 'aoq_details.aoq_head_id', '=', 'aoq_head.id')->where('jo_rfq_vendor.vendor_details_id',$vendor_details_id)->where('jo_rfq_offers.jo_rfq_head_id',$joi_head->jo_rfq_head_id)->where('jo_rfq_offers.awarded','=','1')->where('aoq_status','=','Awarded')->get();
        foreach($joi_labor_details AS $pd){
            // $balance = PrReportDetails::where('jo_labor_details_id',$pd->jo_labor_details_id)->where('status','!=','Cancelled')->first();
            // $total_po=($balance->po_qty + $balance->dpo_qty + $balance->rpo_qty);
            // $totals = $balance->pr_qty - $total_po;
            $total[]=$pd->unit_price * $pd->quantity;
            // $total[]=$pd->unit_price * $balance->pr_qty;
            $jo_rfq_terms=RFQVendorTerms::where('jo_rfq_vendor_id',$pd->jo_rfq_vendor_id)->get();
        }
        $total_sum=array_sum($total);
        return response()->json([
            'vendor'=>$vendor,
            'joi_head'=>$joi_head,
            'jor_head'=>$jor_head,
            'joi_labor_details'=>$joi_labor_details,
            'po_no'=>$po_no,
            'dr_no'=>$dr_no,
            'jo_rfq_terms'=>$jo_rfq_terms,
            'grand_total'=>$total_sum,
            'prepared_by'=>Auth::user()?->name
        ],200);
    }
}
