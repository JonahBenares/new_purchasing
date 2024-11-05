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
use App\Models\PoDrSeries;
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
    public function get_allpo(){
        $po=POHead::orderBy('po_no','ASC')->get();
        $poall=[];
        foreach($po AS $p){
            $identifier=VendorDetails::where('id',$p->vendor_details_id)->value('identifier');
            $poall[]=[
                'id'=>$p->id,
                'status'=>$p->status,
                'po_no'=>$p->po_no,
                date('Y-m-d',strtotime($p->po_date)),
                $p->po_no,
                $p->vendor_name." (".$identifier.")",
                $p->pr_no,
                ($p->method=='PO') ? 'Purchase Order' : (($p->method=='DPO') ? 'Direct Purchase' : 'Repeat Order'),
                ($p->status=='Saved') ? 'PO Issued' : (($p->status=='Draft') ? 'Draft' : 'Cancelled'),
                ''
            ];
        }
        return response()->json([
            'poall'=>$poall,
        ],200);
    }
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

        $dr_series_rows = PoDrSeries::where('year',$year)->count();
        if($dr_series_rows==0){
            $max_dr_series='1';
            $dr_series='0001';
            $dr_no = $year."-".$dr_series.'-'.$company;
        } else {
            $max_dr_series=PoDrSeries::where('year',$year)->max('series');
            $dr_series=$max_dr_series+1;
            $dr_no = $year."-".Str::padLeft($dr_series, 4,'000').'-'.$company;
        }
        $po_head= AOQHead::where('id',$aoq_head_id)->where('pr_no',$pr_no_exp)->first();
        $pr_head= PRHead::where('pr_no',$pr_no_exp)->first();
        $vendor= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$vendor_details_id)->where('status','=','Active')->first();
        $po_details = RFQOffers::select('rfq_offers.id','rfq_offers.rfq_vendor_id', 'remaining_qty', 'rfq_offers.pr_details_id','rfq_offers.offer','rfq_offers.uom','rfq_offers.unit_price','rfq_offers.currency')->join('rfq_vendor', 'rfq_vendor.id', '=', 'rfq_offers.rfq_vendor_id')->join('aoq_details', 'rfq_offers.rfq_vendor_id', '=', 'aoq_details.rfq_vendor_id')->join('aoq_head', 'aoq_details.aoq_head_id', '=', 'aoq_head.id')->where('rfq_vendor.vendor_details_id',$vendor_details_id)->where('rfq_offers.rfq_head_id',$po_head->rfq_head_id)->where('rfq_offers.awarded','=','1')->where('aoq_status','=','Awarded')->get();
        foreach($po_details AS $pd){
            $balance = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('status','!=','Cancelled')->first();
            $total[]=$pd->unit_price * $balance->pr_qty;
            $rfq_terms=RFQVendorTerms::where('rfq_vendor_id',$pd->rfq_vendor_id)->get();
            $pr_rerort_details=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->get();
        }
        $total_sum=array_sum($total);
        return response()->json([
            'vendor'=>$vendor,
            'po_head'=>$po_head,
            'pr_head'=>$pr_head,
            'po_details'=>$po_details,
            'po_no'=>$po_no,
            'dr_no'=>$dr_no,
            'rfq_terms'=>$rfq_terms,
            'grand_total'=>$total_sum,
            'prepared_by'=>Auth::user()?->name
        ],200);
    }

    public function check_balance($pr_details_id){
        $balance = PrReportDetails::where('pr_details_id',$pr_details_id)->where('status','!=','Cancelled')->first();
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
        $exp=explode('-',$request->po_no);
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
        $vendor_name= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$request->vendor_details_id)->where('status','=','Active')->first();
        $data_head['po_date']=date('Y-m-d');
        $data_head['po_no']=$po_no;
        $data_head['pr_no']=$request->pr_no;
        $data_head['vendor_details_id']=$request->vendor_details_id;
        $data_head['vendor_name']=$vendor_name->vendor_name;
        $data_head['shipping_cost']=$request->shipping_cost;
        $data_head['handling_fee']=$request->handling_fee;
        $data_head['discount']=$request->discount;
        $data_head['vat']=$request->vat;
        $data_head['vat_amount']=$request->vat_amount;
        $data_head['vat_percent']=$request->vat_percent;
        $data_head['vat_in_ex']=$request->vat_in_ex;
        $data_head['grand_total']= str_replace(',','',$request->grand_total);
        $data_head['prepared_by']=Auth::id();
        $data_head['checked_by']=$request->checked_by;
        $data_head['recommended_by']=$request->recommended_by;
        $data_head['approved_by']=$request->approved_by;
        $data_head['method']='PO';
        $data_head['status']=$request->status;
        // $data_head['status']='Saved';
        $data_head['user_id']=Auth::id();
        if($request->po_head_id==0){
            //PO Insert
            $insertpohead=POHead::create($data_head);
            //DR Insert
            $series_dr_rows = PoDrSeries::where('year',$year)->count();
            $company=Config::get('constants.company');
            $exp_dr=explode('-',$request->dr_no);
            if($series_dr_rows==0){
                $max_dr_series='1';
                $dr_series='0001';
                $dr_no = $year."-".$dr_series.'-'.$company;
            } else {
                $max_dr_series=PoDrSeries::where('year',$year)->max('series');
                $dr_series=$max_dr_series+1;
                $dr_no = $year."-".Str::padLeft($exp_dr[1], 4,'000').'-'.$company;
            }
            if(!PoDrSeries::where('year',$year)->where('series',$exp_dr[1])->exists()){
                $series['year']=$year;
                $series['series']=$dr_series;
                $po_series=PoDrSeries::create($series);
            }
            $pr_head_id=PRHead::where('pr_no',$request->pr_no)->value('id');
            $site_pr=PRHead::where('pr_no',$request->pr_no)->value('site_pr');
            $data_dr_head['po_head_id']=$insertpohead->id;
            $data_dr_head['pr_head_id']=$pr_head_id;
            $data_dr_head['po_no']=$po_no;
            $data_dr_head['pr_no']=$request->pr_no;
            $data_dr_head['site_pr']=$site_pr;
            $data_dr_head['dr_date']=date('Y-m-d');
            $data_dr_head['dr_no']=$dr_no;
            $data_dr_head['series']=$dr_series;
            $data_dr_head['year']=$year;
            $data_dr_head['status']=$request->status;
            $data_dr_head['user_id']=Auth::id();
            $insertdrhead=PoDr::create($data_dr_head);
        }else{
            $insertpohead=POHead::where('id',$request->po_head_id)->first();
            $insertpohead->update($data_head);

            $data_dr_head['status']=$request->status;
            $insertdrhead=PoDr::where('po_head_id',$request->po_head_id)->first();
            $insertdrhead->update($data_dr_head);
        }
        
        $x=1;
        $y=0;
        $quantitys=array();
        foreach(json_decode($po_details) AS $pd){
            if(count(json_decode($po_details))>0){
                $quantity = $request->input("quantity"."$y");
                $quantitys[] = $request->input("quantity"."$y");
                $data=[
                    'po_head_id'=>$insertpohead->id,
                    'pr_details_id'=>$pd->pr_details_id,
                    'rfq_offers_id'=>$pd->id,
                    'item_no'=>$x,
                    'item_description'=>$pd->offer,
                    'quantity'=> $quantity,
                    'uom'=>$pd->uom,
                    'unit_price'=>$pd->unit_price,
                    'currency'=>$pd->currency,
                    'total_cost'=>$pd->unit_price * $quantity,
                    'status'=>$request->status,
                ];
                if($request->po_head_id==0){
                    $po_details_id=PoDetails::create($data);

                    $data_dr_details=[
                        'po_dr_id'=>$insertdrhead->id,
                        'po_details_id'=>$po_details_id->id,
                        'pr_details_id'=>$pd->pr_details_id,
                        'rfq_offer_id'=>$pd->id,
                        'quantity'=>$quantity,
                    ];
                    $po_dr_items=PoDrItems::create($data_dr_details);
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
                            'quantity'=>$quantity,
                            'uom'=>$pd->uom,
                            'unit_price'=>$pd->unit_price,
                            'currency'=>$pd->currency,
                            'total_cost'=>$pd->unit_price * $quantity,
                            'status'=>$request->status,
                        ]
                    );
                }
                if($request->po_head_id==0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $po_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('po_qty');
                    if($pr_qty > $quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'rfq_offer_id'=>$pd->id,
                        'offer'=>$pd->offer,
                        'pr_qty'=>$pr_qty - $quantity,
                        'po_qty'=>$po_qty + $quantity,
                        'status'=>$po_status
                    ]);
                }else if($request->po_head_id!=0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $po_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('po_qty');
                    if($pr_qty > $quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'rfq_offer_id'=>$pd->id,
                        'offer'=>$pd->offer,
                        'pr_qty'=>$pr_qty - $quantity,
                        'po_qty'=>$po_qty + $quantity,
                        'status'=>$po_status
                    ]);
                }
                $x++;
                $y++;
            }
        }

        foreach(json_decode($rfq_terms) AS $rt){
            if(count(json_decode($rfq_terms))>0){
                $terms = POTerms::where('po_head_id',$insertpohead->id)->where('terms', $rt->terms)->first();
                if(is_null($terms)) {
                    $terms = new POTerms([
                        'po_head_id' => $insertpohead->id,
                        'terms' => $rt->terms
                    ]);
                    $terms->save();
                }else{
                    $terms->terms = $rt->terms;
                    $terms->update();
                }
            }
        }
        
        foreach(json_decode($terms_list) AS $il){
            if(count(json_decode($terms_list))>0){
                $terms = POTerms::where('po_head_id',$insertpohead->id)->where('terms', $il->terms_condition)->first();
                if(is_null($terms)) {
                    $terms = new POTerms([
                        'po_head_id' => $insertpohead->id,
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
                $data_instructions=[
                    'po_head_id'=>$insertpohead->id,
                    'instructions'=>$ol->instructions
                ];
                if(!POInstruction::where('po_head_id',$insertpohead->id)->where('instructions',$ol->instructions)->exists()){
                    $otherinstructions=POInstruction::create($data_instructions);
                }else{
                    $otherinstructions=POInstruction::where('po_head_id',$insertpohead->id)->update($data_instructions);
                }
            }
        }
        echo $insertpohead->id;
    }

    public function po_viewdetails($po_head_id){
        $po_head = POHead::where('id',$po_head_id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Cancelled');
        })->first();
        $pr_head= PRHead::where('pr_no',$po_head->pr_no)->first();
        $po_vendor = VendorDetails::where('id',$po_head->vendor_details_id)->where('status','=','Active')->first();
        $po_details = PoDetails::where('po_head_id',$po_head_id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Cancelled');
        })->get();
        $cancelled_by=User::where('id',$po_head->cancelled_by)->value('name');
        $po_terms = POTerms::where('po_head_id',$po_head_id)->get();
        $po_instructions = POInstruction::where('po_head_id',$po_head_id)->get();
        $prepared_by= User::where('id',$po_head->prepared_by)->value('name');
        $checked_by= User::where('id',$po_head->checked_by)->value('name');
        $recommended_by= User::where('id',$po_head->recommended_by)->value('name');
        $approved_by= User::where('id',$po_head->approved_by)->value('name');
        return response()->json([
            'po_head'=>$po_head,
            'pr_head'=>$pr_head,
            'po_vendor'=>$po_vendor,
            'po_details'=>$po_details,
            'po_terms'=>$po_terms,
            'po_instructions'=>$po_instructions,
            'prepared_by'=>$prepared_by,
            'checked_by'=>$checked_by,
            'recommended_by'=>$recommended_by,
            'approved_by'=>$approved_by,
            'cancelled_by'=>$cancelled_by,
        ],200);
    }

    public function insert_internalcomment(Request $request, $id){
        POHead::where('id',$id)->update([
            'internal_comment'=>$request->internal_comment,
        ]);
    }

    public function cancel_po_items(Request $request, $po_details_id){
        $update_podetails=PoDetails::where('id',$po_details_id)->update([
            'status'=>'Cancelled',
            'cancelled_by'=>Auth::id(),
            'cancelled_date'=>date('Y-m-d h:i:s'),
            'cancelled_reason'=>$request->cancel_reason
        ]);
        if($update_podetails){
            $update_podetails=PoDrItems::where('po_details_id',$po_details_id)->update([
                'status'=>'Cancelled',
            ]);
            $podetails=PoDetails::where('id',$po_details_id)->get();
            foreach($podetails AS $pd){
                $pr_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('pr_qty');
                $po_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('po_qty');
                $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->update([
                    'pr_qty'=>$pr_qty + $pd->quantity,
                    'po_qty'=>$po_qty - $pd->quantity,
                ]);
            }
        }
    }

    public function cancel_all_po(Request $request, $po_head_id){
        $update_head=POHead::where('id',$po_head_id)->update([
            'status'=>'Cancelled',
            'cancelled_by'=>Auth::id(),
            'cancelled_date'=>date('Y-m-d h:i:s'),
            'cancelled_reason'=>$request->cancel_all_reason
        ]);
        $update_head=PoDr::where('po_head_id',$po_head_id)->update([
            'status'=>'Cancelled',
            'cancelled_by'=>Auth::id(),
            'cancelled_date'=>date('Y-m-d h:i:s'),
            'cancelled_reason'=>$request->cancel_all_reason
        ]);
        $update_podetails=PoDetails::where('po_head_id',$po_head_id)->where('status','!=','Cancelled')->update([
            'status'=>'Cancelled',
            'cancelled_by'=>Auth::id(),
            'cancelled_date'=>date('Y-m-d h:i:s'),
            'cancelled_reason'=>$request->cancel_all_reason
        ]);
        if($update_podetails){
            $podetails=PoDetails::where('po_head_id',$po_head_id)->where('status','!=','Cancelled')->get();
            foreach($podetails AS $pd){
                $update_podetails=PoDrItems::where('pr_details_id',$pd->pr_details_id)->where('po_details_id',$pd->id)->where('status','!=','Cancelled')->update([
                    'status'=>'Cancelled',
                ]);
                $pr_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('pr_qty');
                $po_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('po_qty');
                $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->update([
                    'pr_qty'=>$pr_qty + $pd->quantity,
                    'po_qty'=>$po_qty - $pd->quantity,
                ]);
            }
        }
    }
    
}
