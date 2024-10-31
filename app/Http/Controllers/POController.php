<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PRHead;
use App\Models\PrReportDetails;
use App\Models\POHead;
use App\Models\PoDetails;
use App\Models\PoDr;
use App\Models\PoDrItems;
use App\Models\POInstruction;
use App\Models\POTerms;
use App\Models\POSeries;
use App\Models\VendorHead;
use App\Models\VendorDetails;
use App\Models\RFQVendor;
use App\Models\RFQOffers;
use App\Models\RFQVendorTerms;
use App\Models\AOQHead;
use App\Models\AOQDetails;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Config;
class POController extends Controller
{
    public function supplier_dropdown(){
        $suppliers = VendorDetails::select('vendor_details.id','identifier','vendor_name')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('status','=','Active')->get();
        return response()->json([
            'suppliers'=>$suppliers,
        ],200);
    }

    public function get_prno($vendor_details_id){
        // $prno_dropdown = RFQVendor::join('aoq_details', 'rfq_vendor.id', '=', 'aoq_details.rfq_vendor_id')->join('aoq_head', 'aoq_details.aoq_head_id', '=', 'aoq_head.id')->where('vendor_details_id',$vendor_details_id)->where('rfq_vendor.status','=','Saved')->where('aoq_status','=','Awarded')->get();
        $prno_dropdown=RFQOffers::select('rfq_offers.rfq_vendor_id','aoq_head.id','aoq_head.aoq_date','rfq_vendor.pr_no')->distinct()->join('rfq_vendor', 'rfq_vendor.id', '=', 'rfq_offers.rfq_vendor_id')->join('aoq_head', 'rfq_offers.rfq_head_id', '=', 'aoq_head.rfq_head_id')->where('rfq_vendor.vendor_details_id',$vendor_details_id)->where('rfq_offers.awarded','=','1')->where('aoq_status','=','Awarded')->get();
        return response()->json([
            'prno_dropdown'=>$prno_dropdown,
        ],200);
    }

    public function generate_po($vendor_details_id,$pr_no){
        $aoq_head_id_exp=explode('+',$pr_no);
        $pr_no_exp=$aoq_head_id_exp[0];
        $aoq_head_id=$aoq_head_id_exp[1];
        $year=date('Y');
        $series_rows = POSeries::where('year',$year)->count();
        $company=Config::get('constants.company');
        if($series_rows==0){
            $max_series='1';
            $po_series='0001';
            $po_no = 'P'.$pr_no_exp."-".$po_series;
        } else {
            $max_series=POSeries::where('year',$year)->max('series');
            $po_series=$max_series+1;
            $po_no = 'P'.$pr_no_exp."-".Str::padLeft($po_series, 4,'000');
        }
        $po_head= AOQHead::where('id',$aoq_head_id)->where('pr_no',$pr_no_exp)->first();
        $pr_head= PRHead::where('pr_no',$pr_no_exp)->first();
        $vendor= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$vendor_details_id)->where('status','=','Active')->first();
        $po_details = RFQOffers::join('rfq_vendor', 'rfq_vendor.id', '=', 'rfq_offers.rfq_vendor_id')->join('aoq_details', 'rfq_offers.rfq_vendor_id', '=', 'aoq_details.rfq_vendor_id')->join('aoq_head', 'aoq_details.aoq_head_id', '=', 'aoq_head.id')->where('rfq_vendor.vendor_details_id',$vendor_details_id)->where('rfq_offers.rfq_head_id',$po_head->rfq_head_id)->where('rfq_offers.awarded','=','1')->where('aoq_status','=','Awarded')->get();
        foreach($po_details AS $pd){
            $total[]=$pd->unit_price * $pd->remaining_qty;
            $rfq_terms=RFQVendorTerms::where('rfq_vendor_id',$pd->rfq_vendor_id)->get();
        }
        $total_sum=array_sum($total);
        return response()->json([
            'vendor'=>$vendor,
            'po_head'=>$po_head,
            'pr_head'=>$pr_head,
            'po_details'=>$po_details,
            'po_no'=>$po_no,
            'rfq_terms'=>$rfq_terms,
            'grand_total'=>$total_sum,
            'prepared_by'=>Auth::user()?->name
        ],200);
    }

    public function check_balance($pr_details_id){
        $balance = PrReportDetails::where('pr_details_id',$pr_details_id)->where('status','=','Awarded')->first();
        return response()->json([
            'balance'=>$balance,
        ],200);
    }
}
