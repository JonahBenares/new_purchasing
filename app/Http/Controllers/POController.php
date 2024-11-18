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

use App\Models\POHeadTemp;
use App\Models\PoDetailsTemp;
use App\Models\PoDrTemp;
use App\Models\PoDrItemsTemp;
use App\Models\POInstructionsTemp;
use App\Models\POTermsTemp;

use App\Models\PORevisionHead;
use App\Models\PORevisionDetails;
use App\Models\PORevisionDrHead;
use App\Models\PORevisionDrItems;
use App\Models\PORevisionInstructions;
use App\Models\PORevisionTerms;
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
                'revision_no'=>$p->revision_no,
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
        // $suppliers = VendorDetails::select('vendor_details.id','identifier','vendor_name')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('status','=','Active')->get();
        $suppliers = VendorDetails::select('vendor_details.id','identifier','vendor_head.vendor_name')->distinct()->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->join('rfq_vendor', 'vendor_details.id', '=', 'rfq_vendor.vendor_details_id')->join('rfq_offers', 'rfq_vendor.id', '=', 'rfq_offers.rfq_vendor_id')->join('aoq_head', 'rfq_offers.rfq_head_id', '=', 'aoq_head.rfq_head_id')->where('vendor_details.status','=','Active')->where('aoq_status','=','Awarded')->where('awarded','=','1')->get();
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
            $total_po=($balance->po_qty + $balance->dpo_qty + $balance->rpo_qty);
            $totals = $balance->pr_qty - $total_po;
            $total[]=$pd->unit_price * $totals;
            // $total[]=$pd->unit_price * $balance->pr_qty;
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
        $balance = PrReportDetails::where('pr_details_id',$pr_details_id)->first();
        return response()->json([
            'balance'=>$balance,
        ],200);
    }

    public function check_balance_rev($po_head_id,$pr_details_id){
        $balance_overall = PrReportDetails::where('pr_details_id',$pr_details_id)->first();
        $balance = PoDetails::where('po_head_id',$po_head_id)->where('pr_details_id',$pr_details_id)->first();
        return response()->json([
            'balance'=>$balance,
            'balance_overall'=>$balance_overall,
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
                if(($request->po_head_id==0 || $request->po_head_id!=0) && $request->props_id==0 && $request->status=='Saved'){
                    $data=[
                        'po_head_id'=>$insertpohead->id,
                        'pr_details_id'=>$pd->pr_details_id,
                        'rfq_offers_id'=>$pd->id,
                        'item_no'=>$x,
                        // 'item_description'=> ($request->po_head_id==0) ? $pd->offer : (($request->status=='Saved') ? $pd->offer : (($request->props_id!=0) ? $pd->item_description : $pd->offer)),
                        'item_description'=> $pd->offer,
                        'quantity'=>  $quantity,
                        // 'quantity'=>  ($request->po_head_id==0) ? $quantity : (($request->status=='Saved') ? $quantity : (($request->props_id!=0) ? $pd->quantity : $quantity)),
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> $pd->unit_price * $quantity,
                        // 'total_cost'=> ($request->po_head_id==0) ? $pd->unit_price * $quantity : (($request->status=='Saved') ? $pd->unit_price * $quantity : (($request->props_id!=0) ? $pd->unit_price * $pd->quantity : $pd->unit_price * $quantity)),
                        'status'=>$request->status,
                    ];
                }else if(($request->po_head_id==0 || $request->po_head_id!=0) && $request->props_id==0 && $request->status=='Draft'){
                    $data=[
                        'po_head_id'=>$insertpohead->id,
                        'pr_details_id'=>$pd->pr_details_id,
                        'rfq_offers_id'=>$pd->id,
                        'item_no'=>$x,
                        // 'item_description'=> ($request->po_head_id==0) ? $pd->offer : (($request->status=='Saved') ? $pd->offer : (($request->props_id!=0) ? $pd->item_description : $pd->offer)),
                        'item_description'=> $pd->offer,
                        'quantity'=> $quantity,
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> $pd->unit_price * $quantity,
                        'status'=>$request->status,
                    ];
                }else if($request->props_id!=0 && $request->status=='Saved'){
                    $data=[
                        'po_head_id'=>$insertpohead->id,
                        'pr_details_id'=>$pd->pr_details_id,
                        'rfq_offers_id'=>$pd->id,
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
                        'po_head_id'=>$insertpohead->id,
                        'pr_details_id'=>$pd->pr_details_id,
                        'rfq_offers_id'=>$pd->id,
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
                if($request->po_head_id==0){
                    $po_details_id=PoDetails::create($data);
                    if($request->po_head_id==0 && $request->status=='Saved'){
                        $data_dr_details=[
                            'po_dr_id'=>$insertdrhead->id,
                            'po_details_id'=>$po_details_id->id,
                            'pr_details_id'=>$pd->pr_details_id,
                            'rfq_offer_id'=>$pd->id,
                            'to_deliver'=>$quantity,
                            'quantity'=>$quantity,
                        ];
                    }else if($request->po_head_id==0 && $request->status=='Draft'){
                        $data_dr_details=[
                            'po_dr_id'=>$insertdrhead->id,
                            'po_details_id'=>$po_details_id->id,
                            'pr_details_id'=>$pd->pr_details_id,
                            'rfq_offer_id'=>$pd->id,
                            'to_deliver'=>$quantity,
                            'quantity'=>$quantity,
                        ];
                    }
                    $po_dr_items=PoDrItems::create($data_dr_details);
                }else{
                    if($request->props_id==0){
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
                        $po_dr_items=PoDrItems::updateOrCreate(
                            [
                                'po_details_id'=>$po_details_id->id,
                                'rfq_offer_id'=>$pd->id,
                                'pr_details_id'=>$pd->pr_details_id,
                            ],
                            [
                                'to_deliver'=>$quantity,
                                'quantity'=>$quantity,
                            ]
                        );
                    }else{
                        $po_details_id=PoDetails::where('id',$pd->id)->update([
                                'po_head_id'=>$insertpohead->id,
                                'pr_details_id'=>$pd->pr_details_id,
                                'rfq_offers_id'=>$pd->rfq_offers_id,
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
                        $podritems=PoDrItems::where('po_details_id',$pd->id)->where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->get();
                        foreach($podritems AS $pdi){
                            $po_dr_items=PoDrItems::where('id',$pdi->id)->update([
                                    'to_deliver'=>$pd->quantity,
                                    'quantity'=>$pd->quantity,
                                ]
                            );
                        }
                        // $po_dr_items=PoDrItems::updateOrCreate(
                        //     [
                        //         'po_details_id'=>$po_details_id->id,
                        //         'rfq_offer_id'=>$pd->id,
                        //         'pr_details_id'=>$pd->pr_details_id,
                        //     ],
                        //     [
                        //         'quantity'=>($request->po_head_id==0) ? $quantity : (($request->status=='Saved') ? $quantity : (($request->props_id!=0) ? $pd->quantity : $quantity)),
                        //     ]
                        // );
                    }
                }
                if($request->po_head_id==0 && $request->props_id==0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $po_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('po_qty');
                    $dpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('dpo_qty');
                    $rpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('rpo_qty');
                    $total_out=$po_qty+$dpo_qty+$rpo_qty;
                    if($pr_qty > $quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'rfq_offer_id'=>$pd->id,
                        'offer'=>$pd->offer,
                        // 'pr_qty'=>$pr_qty - $quantity,
                        'po_qty'=>$po_qty + $quantity,
                        'status'=>$po_status
                    ]);
                }else if($request->po_head_id!=0 && $request->props_id==0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $po_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('po_qty');
                    $dpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('dpo_qty');
                    $rpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('rpo_qty');
                    $total_out=$po_qty+$dpo_qty+$rpo_qty;
                    if($pr_qty > $quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'rfq_offer_id'=>$pd->id,
                        'offer'=>$pd->offer,
                        // 'pr_qty'=>$pr_qty - $quantity,
                        'po_qty'=>$po_qty + $quantity,
                        'status'=>$po_status
                    ]);
                }else if($request->po_head_id==0 && $request->props_id!=0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $po_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('po_qty');
                    $dpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('dpo_qty');
                    $rpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('rpo_qty');
                    $total_out=$po_qty+$dpo_qty+$rpo_qty;
                    if($pr_qty > $pd->quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $pd->quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'rfq_offer_id'=>$pd->id,
                        'offer'=>$pd->item_description,
                        // 'pr_qty'=>$pr_qty - $quantity,
                        'po_qty'=>$po_qty + $pd->quantity,
                        'status'=>$po_status
                    ]);
                }else if($request->po_head_id!=0 && $request->props_id!=0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $po_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('po_qty');
                    $dpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('dpo_qty');
                    $rpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('rpo_qty');
                    $total_out=$po_qty+$dpo_qty+$rpo_qty;
                    if($pr_qty > $pd->quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $pd->quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'rfq_offer_id'=>$pd->id,
                        'offer'=>$pd->item_description,
                        // 'pr_qty'=>$pr_qty - $quantity,
                        'po_qty'=>$po_qty + $pd->quantity,
                        'status'=>$po_status
                    ]);
                }
                $x++;
                $y++;
            }
        }

        foreach(json_decode($rfq_terms) AS $rt){
            if(count(json_decode($rfq_terms))>0){
                if($request->po_head_id==0){
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
                }else{
                    $terms = POTerms::where('id',$rt->id)->first();
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
                if($request->po_head_id==0){
                    $data_instructions=[
                        'po_head_id'=>$insertpohead->id,
                        'instructions'=>$ol->instructions
                    ];
                    if(!POInstruction::where('po_head_id',$insertpohead->id)->where('instructions',$ol->instructions)->exists()){
                        $otherinstructions=POInstruction::create($data_instructions);
                    }else{
                        $otherinstructions=POInstruction::where('po_head_id',$insertpohead->id)->where('instructions',$ol->instructions)->update($data_instructions);
                    }
                }else{
                    if($ol->id==0){
                        $data_instructions=[
                            'po_head_id'=>$insertpohead->id,
                            'instructions'=>$ol->instructions
                        ];
                        $otherinstructions=POInstruction::create($data_instructions);
                    }else{
                        $otherinstructions = POInstruction::where('id',$ol->id)->first();
                        $otherinstructions->instructions = $ol->instructions;
                        $otherinstructions->update();
                    }
                }
            }
        }
        echo $insertpohead->id;
    }

    public function po_viewdetails($po_head_id){
        $po_head_array = POHead::where('id',$po_head_id)->get();
        $po_head = POHead::where('id',$po_head_id)->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Cancelled')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->first();
        $po_head_temp = POHeadTemp::where('po_head_id',$po_head_id)->first();
        $po_terms_temp = POTermsTemp::where('po_head_id',$po_head_id)->get();
        $po_instruction_temp = POInstructionsTemp::where('po_head_id',$po_head_id)->get();
        $po_dr_array= PoDr::where('po_head_id',$po_head_id)->get();
        $po_dr= PoDr::where('po_head_id',$po_head_id)->first();
        $po_dr_items= PoDrItems::where('po_dr_id',$po_dr->id)->get();
        $pr_head= PRHead::where('pr_no',$po_head->pr_no)->first();
        // $po_vendor = VendorDetails::where('id',$po_head->vendor_details_id)->where('status','=','Active')->first();
        $po_vendor= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$po_head->vendor_details_id)->where('status','=','Active')->first();
        $po_details = PoDetails::where('po_head_id',$po_head_id)->where('quantity','!=','0')->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Cancelled')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $po_details_view = PoDetails::where('po_head_id',$po_head_id)->where('quantity','!=','0')->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $po_details_temp = PoDetailsTemp::where('po_head_id',$po_head_id)->get();
        $cancelled_by=User::where('id',$po_head->cancelled_by)->value('name');
        $po_terms = POTerms::where('po_head_id',$po_head_id)->get();
        $po_instructions = POInstruction::where('po_head_id',$po_head_id)->get();
        $prepared_by= User::where('id',$po_head->prepared_by)->value('name');
        $checked_by= User::where('id',$po_head->checked_by)->value('name');
        $recommended_by= User::where('id',$po_head->recommended_by)->value('name');
        $approved_by= User::where('id',$po_head->approved_by)->value('name');
        $total=[];
        foreach($po_details AS $pd){
            $total[]=$pd->unit_price * $pd->quantity;
        }
        $total_sum=array_sum($total);
        return response()->json([
            'po_head_array'=>$po_head_array,
            'po_head'=>$po_head,
            'po_head_temp'=>$po_head_temp,
            'po_dr_array'=>$po_dr_array,
            'po_dr'=>$po_dr,
            'po_dr_items'=>$po_dr_items,
            'pr_head'=>$pr_head,
            'po_vendor'=>$po_vendor,
            'po_details'=>$po_details,
            'po_details_view'=>$po_details_view,
            'po_details_temp'=>$po_details_temp,
            'po_terms'=>$po_terms,
            'po_terms_temp'=>$po_terms_temp,
            'po_instructions'=>$po_instructions,
            'po_instructions_temp'=>$po_instruction_temp,
            'prepared_by'=>$prepared_by,
            'checked_by'=>$checked_by,
            'recommended_by'=>$recommended_by,
            'approved_by'=>$approved_by,
            'cancelled_by'=>$cancelled_by,
            'grand_total'=>$total_sum,
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
                // $pr_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('pr_qty');
                $po_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('po_qty');
                $dpo_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('dpo_qty');
                $rpo_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('rpo_qty');
                $method = POHead::where('id',$pd->po_head_id)->value('method');
                if($method=='PO'){
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->update([
                        'po_qty'=>$po_qty - $pd->quantity,
                    ]);
                }else if($method=='DPO'){
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->update([
                        'dpo_qty'=>$dpo_qty - $pd->quantity,
                    ]);
                }else if($method=='RPO'){
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->update([
                        'rpo_qty'=>$rpo_qty - $pd->quantity,
                    ]);
                }
            }
        }
    }

    public function cancel_all_po(Request $request, $po_head_id){
        $podetails=PoDetails::where('po_head_id',$po_head_id)->where('status','!=','Cancelled')->get();
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
            foreach($podetails AS $pd){
                $update_podetails=PoDrItems::where('pr_details_id',$pd->pr_details_id)->where('po_details_id',$pd->id)->where('status','!=','Cancelled')->update([
                    'status'=>'Cancelled',
                ]);
                // $pr_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('pr_qty');
                $po_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('po_qty');
                $dpo_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('dpo_qty');
                $rpo_qty = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->value('rpo_qty');
                $method = POHead::where('id',$pd->po_head_id)->value('method');
                if($method=='PO'){
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->update([
                        // 'pr_qty'=>$pr_qty + $pd->quantity,
                        'po_qty'=>$po_qty - $pd->quantity,
                    ]);
                }else if($method=='DPO'){
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->update([
                        'dpo_qty'=>$dpo_qty - $pd->quantity,
                    ]);
                }else if($method=='RPO'){
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->update([
                        'rpo_qty'=>$rpo_qty - $pd->quantity,
                    ]);
                }
            }
        }
    }
    
    public function delete_terms($id){
        $deleted = POTerms::find($id);
        $deleted->delete();
    }

    public function delete_instructions($id){
        $deleted = POInstruction::find($id);
        $deleted->delete();
    }

    public function save_change_po(Request $request){
        $po_dr=$request->input("po_dr");
        $po_dr_items=$request->input("po_dr_items");
        $terms_list=$request->input("terms_list");
        $po_terms=$request->input("po_terms");
        $po_instructions=$request->input("po_instructions");
        $other_list=$request->input("other_list");
        $po_details=$request->input("po_details");
        $data_head=[
            'po_head_id'=>$request->props_id,
            'shipping_cost'=>$request->shipping_cost,
            'handling_fee'=>$request->handling_fee,
            'discount'=>$request->discount,
            'vat'=>$request->vat,
            'vat_percent'=>$request->vat_percent,
            'vat_amount'=>$request->vat_amount,
            'vat_in_ex'=>$request->vat_in_ex,
            'grand_total'=>$request->grand_total,
            'internal_comment'=>$request->internal_comment,
            'revision_no'=>0,
        ];
        $pohead_temp=POHeadTemp::create($data_head);
        $y=0;
        foreach(json_decode($po_details) AS $pd){
            $quantity = $request->input("quantity"."$y");
            $data_details=[
                'po_details_id'=>$pd->id,
                'po_head_id'=>$pd->po_head_id,
                'pr_details_id'=>$pd->pr_details_id,
                'rfq_offers_id'=>$pd->rfq_offers_id,
                'item_description'=>$pd->item_description,
                'uom'=>$pd->uom,
                'unit_price'=>$pd->unit_price,
                'currency'=>$pd->currency,
                'quantity'=>$quantity,
                'total_cost'=>$pd->unit_price * $quantity,
            ];
            $podetails_temp=PoDetailsTemp::create($data_details);
            $y++;
        }

        if(count(json_decode($po_terms))>0){
            foreach(json_decode($po_terms) AS $pt){
                
                $data_terms=[
                    'po_terms_id' => $pt->id,
                    'po_head_id'=>$request->props_id,
                    'terms'=>$pt->terms,
                ];
                $po_terms_temp=POTermsTemp::create($data_terms);
            }
        }

        if(count(json_decode($terms_list))>0){
            foreach(json_decode($terms_list) AS $il){
           
                $data_terms1=[
                    'po_terms_id' => '0',
                    'po_head_id' => $request->props_id,
                    'terms' => $il->terms_condition
                ];
                $po_terms_temp1=POTermsTemp::create($data_terms1);
            }
        }

        if(count(json_decode($po_instructions))>0){
            foreach(json_decode($po_instructions) AS $pi){
                $data_ins=[
                    'po_instruction_id'=>$pi->id,
                    'po_head_id'=>$request->props_id,
                    'instructions'=>$pi->instructions,
                ];
                $po_ins_temp=POInstructionsTemp::create($data_ins);
            }
        }

        if(count(json_decode($other_list))>0){
            foreach(json_decode($other_list) AS $ol){
                $data_ins1=[
                    'po_instruction_id'=>'0',
                    'po_head_id' => $request->props_id,
                    'instructions' => $ol->instructions
                ];
                $po_terms_temp1=POInstructionsTemp::create($data_ins1);
            }
        }

        $data_head=POHead::where('id',$request->props_id)->update([
            'status'=>'Revised',
        ]);
    }

    public function save_approved_revision(Request $request){
        $po_head=$request->input("po_head");
        $po_dr=$request->input("po_dr");
        $po_dr_items=$request->input("po_dr_items");
        $terms_list=$request->input("terms_list");
        $po_terms=$request->input("po_terms");
        $po_instructions=$request->input("po_instructions");
        $other_list=$request->input("other_list");
        $po_details=$request->input("po_details");
        $revision_max=POHead::where('id',$request->props_id)->max('revision_no');
        $revision_no=$revision_max+1;
        foreach(json_decode($po_head) AS $ph){
            $data_head=[
                'po_head_id'=>$ph->id,
                'pr_no'=>$ph->pr_no,
                'vendor_details_id'=>$ph->vendor_details_id,
                'vendor_name'=>$ph->vendor_name,
                'po_no'=>$ph->po_no,
                'po_date'=>$ph->po_date,
                'shipping_cost'=>$ph->shipping_cost,
                'handling_fee'=>$ph->handling_fee,
                'discount'=>$ph->discount,
                'vat'=>$ph->vat,
                'vat_amount'=>$ph->vat_amount,
                'vat_percent'=>$ph->vat_percent,
                'vat_in_ex'=>$ph->vat_in_ex,
                'grand_total'=>$ph->grand_total,
                'prepared_by'=>$ph->prepared_by,
                'checked_by'=>$ph->checked_by,
                'recommended_by'=>$ph->recommended_by,
                'approved_by'=>$ph->approved_by,
                'user_id'=>$ph->user_id,
                'method'=>$ph->method,
                'revision_no'=>$ph->revision_no ?? 0,
                'status'=>$ph->status,
                'internal_comment'=>$ph->internal_comment,
                'cancelled_date'=>$ph->cancelled_date,
                'cancelled_by'=>$ph->cancelled_by,
                'cancelled_reason'=>$ph->cancelled_reason,
            ];
            $po_revision_head=PORevisionHead::create($data_head);
        }

        foreach(json_decode($po_details) AS $pd){
            $data_details=[
                'po_head_rev_id'=>$po_revision_head->id,
                'po_head_id'=>$pd->po_head_id,
                'item_no'=>$pd->item_no,
                'pr_details_id'=>$pd->pr_details_id,
                'rfq_offers_id'=>$pd->rfq_offers_id,
                'reference_po_details_id'=>$pd->reference_po_details_id,
                'reference_po_no'=>$pd->reference_po_no,
                'item_description'=>$pd->item_description,
                'quantity'=>$pd->quantity,
                'uom'=>$pd->uom,
                'unit_price'=>$pd->unit_price,
                'total_cost'=>$pd->total_cost,
                'currency'=>$pd->currency,
                'cancelled_date'=>$pd->cancelled_date,
                'cancelled_by'=>$pd->cancelled_by,
                'cancelled_reason'=>$pd->cancelled_reason,
                'status'=>$pd->status,
            ];
            $po_revision_details=PORevisionDetails::create($data_details);
        }

        foreach(json_decode($po_dr) AS $pdr){
            $data_dr=[
                'po_head_rev_id'=>$po_revision_head->id,
                'po_dr_id'=>$pdr->id,
                'po_head_id'=>$pdr->po_head_id,
                'pr_head_id'=>$pdr->pr_head_id,
                'po_no'=>$pdr->po_no,
                'pr_no'=>$pdr->pr_no,
                'site_pr'=>$pdr->site_pr,
                'dr_date'=>$pdr->dr_date,
                'dr_no'=>$pdr->dr_no,
                'status'=>$pdr->status,
                'delivery_date'=>$pdr->delivery_date,
                'user_id'=>$pdr->user_id,
                'cancelled_date'=>$pdr->cancelled_date,
                'cancelled_by'=>$pdr->cancelled_by,
                'cancelled_reason'=>$pdr->cancelled_reason,
                'revision_no'=>$pdr->revision_no
            ];
            $po_revision_dr=PORevisionDrHead::create($data_dr);
        }

        foreach(json_decode($po_dr_items) AS $pdri){
            $data_dritems=[
                'po_head_rev_id'=>$po_revision_head->id,
                'po_dr_rev_id'=>$po_revision_dr->id,
                'po_dr_id'=>$pdri->po_dr_id,
                'po_details_id'=>$pdri->po_details_id,
                'pr_details_id'=>$pdri->pr_details_id,
                'rfq_offer_id'=>$pdri->rfq_offer_id,
                'quantity'=>$pdri->quantity,
                'status'=>$pdri->status
            ];
            $po_revision_dritems=PORevisionDrItems::create($data_dritems);
        }
        foreach(json_decode($po_terms) AS $pt){
            $data_terms=[
                'po_head_rev_id'=>$po_revision_head->id,
                'po_head_id'=>$pt->po_head_id,
                'terms'=>$pt->terms
            ];
            $po_revision_terms=PORevisionTerms::create($data_terms);
        }
        foreach(json_decode($po_instructions) AS $pi){
            $data_instructions=[
                'po_head_rev_id'=>$po_revision_head->id,
                'po_head_id'=>$pi->po_head_id,
                'instructions'=>$pi->instructions
            ];
            $po_revision_instructions=PORevisionInstructions::create($data_instructions);
        }

        if($po_revision_head){
            $po_head_temp=POHeadTemp::where('po_head_id',$request->props_id)->first();
            $data_head=POHead::where('id',$request->props_id)->update([
                'approved_by_rev'=>$request->approved_by_rev,
                'approved_date'=>$request->approved_date,
                'approved_reason'=>$request->approved_reason,
                'shipping_cost'=>$po_head_temp->shipping_cost,
                'handling_fee'=>$po_head_temp->handling_fee,
                'discount'=>$po_head_temp->discount,
                'vat'=>$po_head_temp->vat,
                'vat_percent'=>$po_head_temp->vat_percent,
                'vat_amount'=>$po_head_temp->vat_amount,
                'vat_in_ex'=>$po_head_temp->vat_in_ex,
                'grand_total'=>$po_head_temp->grand_total,
                'internal_comment'=>$po_head_temp->internal_comment,
                'revision_no'=>$revision_no,
                'status'=>'Saved',
            ]);
            
            foreach(json_decode($po_dr) AS $pdr){
                $data_dr=PoDr::where('id',$pdr->id)->update([
                    'revision_no'=>$revision_no,
                ]);
            }
            $y=0;
            $po_details_temp=PoDetailsTemp::where('po_head_id',$request->props_id)->get();
            foreach($po_details_temp AS $pd){
                $data_details=PoDetails::where('id',$pd->po_details_id)->update([
                    'quantity'=>$pd->quantity,
                    'total_cost'=>$pd->total_cost,
                ]);
                $data_dr_details=PoDrItems::where('po_details_id',$pd->po_details_id)->where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offers_id)->update([
                    'quantity'=>$pd->quantity,
                ]);

                $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                $po_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('po_qty');
                $dpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('dpo_qty');
                $rpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('rpo_qty');
                $method = POHead::where('id',$pd->po_head_id)->value('method');
                $total_out=$po_qty + $dpo_qty + $rpo_qty;
                if($pr_qty > $pd->quantity){
                    $po_status='PO Issued Partially';
                }else if($pr_qty == $pd->quantity){
                    $po_status='PO Issued Fully';
                }

                if($method=='PO'){
                    $difference = $pd->quantity-$po_qty;
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->first();
                    $update_prreport->po_qty += $difference;
                    $update_prreport->status = $po_status;
                    $update_prreport->update();
                    //$total = $pd->quantity - $pd->quantity;
                    // if($po_qty!=$pd->quantity){
                    //     // if($po_qty >= $pd->quantity){
                    //         $total= ($pd->quantity<$po_qty) ? $pd->quantity+$po_qty : $po_qty - $pd->quantity;
                    //         // if($pr_qty!=$total_out){
                    //             // $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                    //             //     'po_qty'=>($pd->quantity>$po_qty) ? $total : $po_qty-$total,
                    //             //     'status'=>$po_status
                    //             // ]);
                    //             $difference = $pd->quantity-$po_qty;
                    //             $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->first();
                    //             $update_prreport->po_qty += $difference;
                    //             $update_prreport->update();

                    //         // }
                    //     // }else{
                    //     //     $total=$po_qty-$pd->quantity;
                    //     //     $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                    //     //         'po_qty'=> $total,
                    //     //         'status'=>$po_status
                    //     //     ]);
                    //     // }
                    // }
                }else if($method=='DPO'){
                    $difference = $pd->quantity-$dpo_qty;
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->first();
                    $update_prreport->dpo_qty += $difference;
                    $update_prreport->status = $po_status;
                    $update_prreport->update();
                    // if($pd->quantity!=$pd->quantity){
                    //     if($dpo_qty > $pd->quantity){
                    //         $total=$dpo_qty-$pd->quantity;
                    //         $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                    //             'dpo_qty'=>$dpo_qty - $total,
                    //             'status'=>$po_status
                    //         ]);
                    //     }else{
                    //         $total=$dpo_qty+$pd->quantity;
                    //         $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                    //             'dpo_qty'=> $total - $dpo_qty,
                    //             'status'=>$po_status
                    //         ]);
                    //     }
                    // }
                }else if($method=='RPO'){
                    $difference = $pd->quantity-$rpo_qty;
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->first();
                    $update_prreport->rpo_qty += $difference;
                    $update_prreport->status = $po_status;
                    $update_prreport->update();
                    // if($pd->quantity!=$pd->quantity){
                    //     if($rpo_qty > $pd->quantity){
                    //         $total=$rpo_qty-$pd->quantity;
                    //         $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                    //             'rpo_qty'=>$rpo_qty - $total,
                    //             'status'=>$po_status
                    //         ]);
                    //     }else{
                    //         $total=$rpo_qty+$pd->quantity;
                    //         $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                    //             'rpo_qty'=> $total - $rpo_qty,
                    //             'status'=>$po_status
                    //         ]);
                    //     }
                    // }
                }   
                $y++;
            }
            $po_terms_tempd=POTermsTemp::where('po_head_id',$request->props_id)->get();
            foreach($po_terms_tempd AS $ptt){
                if($ptt->po_terms_id!=0){
                    $update_terms=POTerms::where('id',$ptt->po_terms_id)->update([
                        'terms'=>$ptt->terms
                    ]);
                }else{
                    $terms = new POTerms([
                        'po_head_id' => $ptt->po_head_id,
                        'terms' => $ptt->terms
                    ]);
                    $terms->save();
                }
            }
            $po_instruction_tempd=POInstructionsTemp::where('po_head_id',$request->props_id)->get();
            foreach($po_instruction_tempd AS $pid){
                if($pid->po_instruction_id!=0){
                    $update_instructions=POInstruction::where('id',$pid->po_instruction_id)->update([
                        'instructions'=>$pid->instructions
                    ]);
                }else{
                    $others = new POInstruction([
                        'po_head_id' => $pid->po_head_id,
                        'instructions' => $pid->instructions
                    ]);
                    $others->save();
                }
            }

            $deletedhead = POHeadTemp::where('po_head_id',$request->props_id);
            $deletedhead->delete();
            $deleteddetails = PoDetailsTemp::where('po_head_id',$request->props_id);
            $deleteddetails->delete();
            $deletedterms = POTermsTemp::where('po_head_id',$request->props_id);
            $deletedterms->delete();
            $deletedins = POInstructionsTemp::where('po_head_id',$request->props_id);
            $deletedins->delete();
        }
    }

    public function old_revision_data($po_head_rev_id){
        $po_head_rev = PORevisionHead::where('po_head_id',$po_head_rev_id)->get();
        return response()->json([
            'po_head_rev'=>$po_head_rev,
        ],200);
    }

    public function view_revision_data($po_head_rev_id){
        $po_head = PORevisionHead::where('id',$po_head_rev_id)->first();
        $po_head_rev = PORevisionHead::where('id',$po_head_rev_id)->get();
        $po_details_rev = PORevisionDetails::where('po_head_rev_id',$po_head_rev_id)->get();
        $po_dr_rev = PORevisionDrHead::where('po_head_rev_id',$po_head_rev_id)->first();
        $po_dritems_rev = PORevisionDrItems::where('po_head_rev_id',$po_head_rev_id)->get();
        $po_terms_rev = PORevisionTerms::where('po_head_rev_id',$po_head_rev_id)->get();
        $po_instructions_rev = PORevisionInstructions::where('po_head_rev_id',$po_head_rev_id)->get();
        $pr_head= PRHead::where('pr_no',$po_head->pr_no)->first();
        $po_vendor= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$po_head->vendor_details_id)->where('status','=','Active')->first();
        $cancelled_by=User::where('id',$po_head->cancelled_by)->value('name');
        $prepared_by= User::where('id',$po_head->prepared_by)->value('name');
        $checked_by= User::where('id',$po_head->checked_by)->value('name');
        $recommended_by= User::where('id',$po_head->recommended_by)->value('name');
        $approved_by= User::where('id',$po_head->approved_by)->value('name');
        return response()->json([
            'po_head_rev'=>$po_head,
            'pr_head'=>$pr_head,
            'po_vendor'=>$po_vendor,
            'po_details_rev'=>$po_details_rev,
            'po_dr_rev'=>$po_dr_rev,
            'po_dritems_rev'=>$po_dritems_rev,
            'po_terms_rev'=>$po_terms_rev,
            'po_instructions_rev'=>$po_instructions_rev,
            'prepared_by'=>$prepared_by,
            'checked_by'=>$checked_by,
            'recommended_by'=>$recommended_by,
            'approved_by'=>$approved_by,
            'cancelled_by'=>$cancelled_by,
        ],200);
    }

    public function get_alldr(){
        $dr=PoDr::orderBy('dr_no','ASC')->get();
        $drall=[];
        foreach($dr AS $d){
            $method=POHead::where('id',$d->po_head_id)->value('method');
            $drall[]=[
                'id'=>$d->id,
                "<center>".date('F d,Y',strtotime($d->dr_date))."</center>",
                "<center>".$d->dr_no."</center>",
                "<center>".$d->po_no.(($d->revision_no!=0) ? '.r'.$d->revision_no : '')."</center>",
                ($method=='PO') ? '<center>Purchase Order</center>' : (($method=='DPO') ? '<center>Direct Purchase</center>' : '<center>Repeat Order</center>'),
                ''
            ];
        }
        return response()->json([
            'drall'=>$drall,
        ],200);
    }

    public function po_dropdown(){
        $po_dropdown = PoDr::select('po_head_id','po_no','revision_no')->distinct()->where('status','Saved')->get();
        return response()->json([
            'po_dropdown'=>$po_dropdown,
        ],200);
    }

    public function generate_dr($po_head_id){
        $year=date('Y');
        $company=Config::get('constants.company');
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
        $po_dr = PoDr::where('po_head_id',$po_head_id)->first();
        $po_dr_distinct = PoDr::select('po_head_id','pr_head_id','po_no','pr_no','site_pr','revision_no')->distinct()->where('po_head_id',$po_head_id)->get();
        $count_po_head_id = PoDr::where('po_head_id',$po_head_id)->count();
        $enduse=PRHead::where('id',$po_dr->pr_head_id)->value('enduse');
        $purpose=PRHead::where('id',$po_dr->pr_head_id)->value('purpose');
        $requestor=PRHead::where('id',$po_dr->pr_head_id)->value('requestor');
        $po_head = POHead::where('id',$po_head_id)->first();
        $prepared_by=Auth::user()?->name;
        $vendor=VendorDetails::select('vendor_details.id','identifier','vendor_name')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$po_head->vendor_details_id)->where('status','=','Active')->first();
        $po_dr_items=PoDrItems::where('po_dr_id',$po_dr->id)->get();
        $total_delivered=[];
        foreach($po_dr_items AS $pdi){
            $delivered_qty=PrReportDetails::where('pr_details_id',$pdi->pr_details_id)->where('rfq_offer_id',$pdi->rfq_offer_id)->value('delivered_qty');
            $total_delivered[]=$delivered_qty;
            // $total_delivered[]=$pdi->delivered_qty;
        }
        $total_sumdelivered=array_sum($total_delivered);
        return response()->json([
            'dr_no'=>$dr_no,
            'po_dr'=>$po_dr,
            'po_dr_mult'=>$po_dr_distinct,
            'count_po_head_id'=>$count_po_head_id,
            'po_dr_items'=>$po_dr_items,
            'enduse'=>$enduse,
            'purpose'=>$purpose,
            'requestor'=>$requestor,
            'vendor'=>$vendor,
            'prepared_by'=>$prepared_by,
            'total_sumdelivered'=>$total_delivered,
        ],200);
    }

    public function get_dr_view($po_dr_id){
        $po_dr = PoDr::where('id',$po_dr_id)->where('status','Saved')->first();
        $po_dr_items = PoDrItems::where('po_dr_id',$po_dr_id)->get();
        $enduse=PRHead::where('id',$po_dr->pr_head_id)->value('enduse');
        $purpose=PRHead::where('id',$po_dr->pr_head_id)->value('purpose');
        $requestor=PRHead::where('id',$po_dr->pr_head_id)->value('requestor');
        $po_head = POHead::where('id',$po_dr->po_head_id)->first();
        $prepared_by=User::where('id',$po_dr->user_id)->value('name');
        $vendor=VendorDetails::select('vendor_details.id','identifier','vendor_name')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$po_head->vendor_details_id)->where('status','=','Active')->first();
        return response()->json([
            'po_dr'=>$po_dr,
            'po_dr_items'=>$po_dr_items,
            'enduse'=>$enduse,
            'purpose'=>$purpose,
            'requestor'=>$requestor,
            'prepared_by'=>$prepared_by,
            'vendor'=>$vendor,
        ],200);
    }


    public function get_offer($rfq_offer_id){
        $offer = RFQOffers::where('id',$rfq_offer_id)->where('awarded','1')->first();
        return response()->json([
            'offer'=>$offer,
        ],200);
    }

    public function check_dr_balance($po_dr_id,$po_details_id){
        $balance = PoDrItems::where('po_dr_id',$po_dr_id)->where('po_details_id',$po_details_id)->first();
        $balance_delivered = PoDrItems::where('po_details_id',$po_details_id)->sum('delivered_qty');
        return response()->json([
            'balance'=>$balance->quantity - $balance_delivered,
        ],200);
    }

    public function check_remaining_dr_balance($po_details_id){
        $balance_sum = PoDrItems::where('po_details_id',$po_details_id)->sum('delivered_qty');
        return response()->json([
            'balance_sum'=>$balance_sum,
        ],200);
    }

    public function save_dr(Request $request){
        
        if($request->identifier==0){
            $y=0;
            $updatedrhead=PoDr::where('id',$request->po_dr_id)->first();
            $data_dr_head['delivery_date']=date('Y-m-d');
            $data_dr_head['driver']=$request->driver;
            $data_dr_head['identifier']='1';
            $updatedrhead->update($data_dr_head);
            foreach(json_decode($request->po_dr_items) AS $pd){
                $to_deliver = $request->input("to_deliver"."$y");
                $remaining_delivery = $request->input("remaining_qty"."$y");
                $po_dr_items_details=PoDrItems::where('id',$pd->id)->update([
                    'delivered_qty'=>$to_deliver,
                    'to_deliver'=>$remaining_delivery - $to_deliver,
                ]);
                $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offer_id)->first();
                $check_delivered=$update_prreport->delivered_qty + $quantity;
                if($update_prreport->pr_qty > $check_delivered){
                    $delivery_status='Partially Delivered';
                }else if($update_prreport->pr_qty == $check_delivered){
                    $delivery_status='Fully Delivered';
                }
                $update_prreport->delivered_qty += $to_deliver;
                $update_prreport->status = $delivery_status;
                $update_prreport->update();
                $y++;
            }
            echo $updatedrhead->id;
        }else{
            $year=date('Y');
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
            foreach(json_decode($request->po_dr) AS $podr){
                $po_dr['po_head_id']=$podr->po_head_id;
                $po_dr['pr_head_id']=$podr->pr_head_id;
                $po_dr['po_no']=$podr->po_no;
                $po_dr['pr_no']=$podr->pr_no;
                $po_dr['site_pr']=$podr->site_pr;
                $po_dr['dr_date']=date('Y-m-d');
                $po_dr['dr_no']=$dr_no;
                $po_dr['status']='Saved';
                $po_dr['delivery_date']=date('Y-m-d');
                $po_dr['driver']=$request->driver;
                $po_dr['revision_no']=$podr->revision_no;
                $po_dr['user_id']=Auth::id();
                $po_drinsert=PoDr::create($po_dr);
                $y=0;
                foreach(json_decode($request->po_dr_items) AS $pd){
                    $to_deliver = $request->input("to_deliver"."$y");
                    $remaining_delivery = $request->input("remaining_qty"."$y");
                    if($to_deliver!=0){
                        $po_dr_itmins['po_dr_id']=$po_drinsert->id;
                        $po_dr_itmins['po_details_id']=$pd->po_details_id;
                        $po_dr_itmins['pr_details_id']=$pd->pr_details_id;
                        $po_dr_itmins['rfq_offer_id']=$pd->rfq_offer_id;
                        $po_dr_itmins['quantity']=$pd->quantity;
                        $po_dr_itmins['to_deliver']=$remaining_delivery - $to_deliver;
                        $po_dr_itmins['delivered_qty']=$to_deliver;
                        $po_drinsertitem=PoDrItems::create($po_dr_itmins);
                        $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',$pd->rfq_offer_id)->first();
                        $check_delivered=$update_prreport->delivered_qty + $quantity;
                        if($update_prreport->pr_qty > $check_delivered){
                            $delivery_status='Partially Delivered';
                        }else if($update_prreport->pr_qty == $check_delivered){
                            $delivery_status='Fully Delivered';
                        }
                        $update_prreport->delivered_qty += $to_deliver;
                        $update_prreport->status = $delivery_status;
                        $update_prreport->update();
                    }
                    $y++;
                }
                echo $po_drinsert->id;
            }
        }
    }
}
