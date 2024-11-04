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
use Illuminate\Support\Str;
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
        $po_details = RFQOffers::select('rfq_offers.id','rfq_offers.rfq_vendor_id', 'remaining_qty', 'rfq_offers.pr_details_id','rfq_offers.offer','rfq_offers.uom','rfq_offers.unit_price','rfq_offers.currency')->join('rfq_vendor', 'rfq_vendor.id', '=', 'rfq_offers.rfq_vendor_id')->join('aoq_details', 'rfq_offers.rfq_vendor_id', '=', 'aoq_details.rfq_vendor_id')->join('aoq_head', 'aoq_details.aoq_head_id', '=', 'aoq_head.id')->where('rfq_vendor.vendor_details_id',$vendor_details_id)->where('rfq_offers.rfq_head_id',$po_head->rfq_head_id)->where('rfq_offers.awarded','=','1')->where('aoq_status','=','Awarded')->get();
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

    public function save_po(Request $request){
        $terms_list=$request->input("terms_list");
        $rfq_terms=$request->input("rfq_terms");
        $other_list=$request->input("other_list");
        $po_details=$request->input("po_details");
        $year=date('Y');
        $series_rows = POSeries::where('year',$year)->count();
        $exp=explode('-',$request->pr_no);
        if($series_rows==0){
            $max_series='1';
            $po_series='0001';
            $po_no = 'P'.$request->pr_no."-".$po_series;
        } else {
            $max_series=POSeries::where('year',$year)->max('series');
            $po_series=$max_series+1;
            $po_no = 'P'.$request->pr_no."-".Str::padLeft($exp[3], 4,'000');
        }
        if(!POSeries::where('year',$year)->where('series',$exp[3])->exists()){
            $series['year']=$year;
            $series['series']=$po_series;
            $po_series=POSeries::create($series);
        }
        $data_head['po_no']=$po_no;
        $data_head['pr_no']=$request->pr_no;
        $data_head['vendor_details_id']=$request->vendor_details_id;
        $data_head['shipping_cost']=$request->shipping_cost;
        $data_head['handling_fee']=$request->handling_fee;
        $data_head['discount']=$request->discount;
        $data_head['vat']=$request->vat;
        $data_head['vat_amount']=$request->vat_amount;
        $data_head['vat_percent']=$request->vat_percent;
        $data_head['vat_in_ex']=$request->vat_in_ex;
        $data_head['grand_total']=$request->grand_total;
        $data_head['prepared_by']=Auth::id();
        $data_head['checked_by']=$request->checked_by;
        $data_head['recommended_by']=$request->recommended_by;
        $data_head['approved_by']=$request->approved_by;
        $data_head['method']='PO';
        $data_head['status']='Saved';
        $data_head['user_id']=Auth::id();
        if($request->po_head_id==0){
            $insertpohead=POHead::create($data_head);
        }else{
            $insertpohead=POHead::where('id',$request->po_head_id)->first();
            $insertpohead->update($data_head);
        }
        $x=1;
        foreach(json_decode($po_details) AS $pd){
            if(count(json_decode($po_details))>0){
                $data=[
                    'po_head_id'=>$insertpohead->id,
                    'pr_details_id'=>$pd->pr_details_id,
                    'rfq_offers_id'=>$pd->id,
                    'item_no'=>$x,
                    'item_description'=>$pd->offer,
                    'quantity'=>$pd->remaining_qty,
                    'uom'=>$pd->uom,
                    'unit_price'=>$pd->unit_price,
                    'currency'=>$pd->currency,
                    'total_cost'=>$pd->unit_price * $pd->remaining_qty,
                    'status'=>'Saved',
                ];
                if($request->po_head_id==0){
                    $po_details_id=PoDetails::create($data);
                }else{
                    $po_details_id=PoDetails::updateOrCreate(
                        [
                            'po_head_id' => $insertpohead->id,
                            'rfq_offers_id'=>$pd->id,
                            'pr_details_id'=>$pd->pr_details_id,
                        ],
                        [
                            'po_head_id'=>$insertpohead->id,
                            'pr_details_id'=>$pd->pr_details_id,
                            'rfq_offers_id'=>$pd->id,
                            'item_no'=>$x,
                            'item_description'=>$pd->offer,
                            'quantity'=>$pd->remaining_qty,
                            'uom'=>$pd->uom,
                            'unit_price'=>$pd->unit_price,
                            'currency'=>$pd->currency,
                            'total_cost'=>$pd->unit_price * $pd->remaining_qty,
                            'status'=>'Saved',
                        ]
                    );
                }
                $x++;
            }
        }
        foreach(json_decode($rfq_terms) AS $rt){
            if(count(json_decode($rfq_terms))>0){
                $data_rfqterms1=[
                    'po_head_id'=>$insertpohead->id,
                    'terms'=>$rt->terms
                ];
                if(!POTerms::where('po_head_id',$insertpohead->id)->where('terms',$rt->terms)->exists()){
                    $rfqterms1=POTerms::create($data_rfqterms1);
                }else{
                    $rfqterms1=POTerms::where('po_head_id',$insertpohead->id)->update($data_rfqterms1);
                }
            }
        }
        foreach(json_decode($terms_list) AS $il){
            if(count(json_decode($terms_list))>0){
                $data_rfqterms2=[
                    'po_head_id'=>$insertpohead->id,
                    'terms'=>$rt->terms
                ];
                if(!POTerms::where('po_head_id',$insertpohead->id)->where('terms',$rt->terms)->exists()){
                    $rfqterms2=POTerms::create($data_rfqterms2);
                }else{
                    $rfqterms2=POTerms::where('po_head_id',$insertpohead->id)->update($data_rfqterms2);
                }
            }
        }
        
        foreach(json_decode($other_list) AS $ol){
            if(count(json_decode($other_list))>0){
                $data_instructions=[
                    'po_head_id'=>$insertpohead->id,
                    'instructions'=>$ol->other_ins
                ];
                if(!POInstruction::where('po_head_id',$insertpohead->id)->where('instructions',$ol->other_ins)->exists()){
                    $otherinstructions=POInstruction::create($data_instructions);
                }else{
                    $otherinstructions=POInstruction::where('po_head_id',$insertpohead->id)->update($data_instructions);
                }
            }
        }
    }
}
