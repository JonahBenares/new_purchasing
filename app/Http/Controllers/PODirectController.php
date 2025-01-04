<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PRHead;
use App\Models\PRDetails;
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
use App\Models\VendorTerms;
use App\Models\AOQHead;
use App\Models\AOQDetails;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Config;

class PODirectController extends Controller
{
    public function get_direct_pr(){
        $available_pr=PRHead::where('status', 'Saved')->get();
        $prno_dropdown=array();
        foreach($available_pr AS $apr){
            $count_available_pr = PrReportDetails::where('pr_no',$apr->pr_no)->whereColumn('pr_qty','!=','delivered_qty')->where('status','!=','Cancelled')->get();
            $count_pr=$count_available_pr->count();
            if($count_pr != 0 || $count_pr != ''){
                $prno_dropdown[] = [
                    'id'=>$apr->id,
                    'pr_no'=>$apr->pr_no,
                ];
            }
        }
        return response()->json([
            'prno_dropdown'=>$prno_dropdown,
        ],200);
    }

    public function direct_supplier_dropdown(){
        $dpr_suppliers=VendorDetails::with('vendor_head')->where('status', 'Active')->get();
        $suppliers=array();
        foreach($dpr_suppliers AS $dprs){
                $suppliers[] = [
                    'id'=>$dprs->id,
                    'vendor_name'=>$dprs->vendor_head->vendor_name,
                    'identifier'=>$dprs->identifier,
                ];
        }
        return response()->json([
            'suppliers'=>$suppliers,
        ],200);
    }

    public function generate_dpo($pr_no,$vendor_details_id){
        $year=date('Y');
        $series_rows = POSeries::where('year',$year)->count();
        $company=Config::get('constants.company');
        if($series_rows==0){
            $max_series='1';
            $po_series='0001';
            $po_no = 'P'.$pr_no."-".$po_series;
        } else {
            $max_series=POSeries::where('year',$year)->max('series');
            $po_series=$max_series+1;
            $po_no = 'P'.$pr_no."-".Str::padLeft($po_series, 4,'000');
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

        // $vendor_details_id_exp=explode('+',$vendor_details_id);
        // $vendordetailsid=$vendor_details_id_exp[0];

        // $po_head= AOQHead::where('id',$aoq_head_id)->where('pr_no',$pr_no)->first();
        $vendor_terms=VendorTerms::where('vendor_details_id',$vendor_details_id)->get();
        $pr_head= PRHead::where('pr_no',$pr_no)->first();
        $vendor_list= VendorDetails::with('vendor_head')->where('id',$vendor_details_id)->get();
        foreach($vendor_list AS $vl){
            $vendor = [
                'vendor_name' =>$vl->vendor_head->vendor_name,
                'vendor_head_id'=>$vl->vendor_head_id,
                'phone'=>$vl->phone,
                'fax'=>$vl->fax,
                'contact_person'=>$vl->contact_person,
                'address'=>$vl->address,
                'identifier'=>$vl->identifier,
            ];
        }

        // $pr_details = PrReportDetails::where('pr_no',$pr_no)->whereColumn('pr_qty','!=','delivered_qty')->where('status','!=','Cancelled')->get();
        $pr_head_id= PRHead::where('pr_no',$pr_no)->value('id');
        $pr_details = PRDetails::where('pr_head_id',$pr_head_id)->where('status','=','Saved')->get();
        $currency=Config::get('constants.currency');
        foreach($pr_details AS $pd){
            $po_qty= PrReportDetails::where('pr_details_id',$pd->id)->value('po_qty');
            $dpo_qty= PrReportDetails::where('pr_details_id',$pd->id)->value('dpo_qty');
            $rpo_qty= PrReportDetails::where('pr_details_id',$pd->id)->value('rpo_qty');
            $available_qty = $pd->pr_qty - ($po_qty + $dpo_qty + $rpo_qty);
            if($available_qty > 0){
                $po_details[] = [
                    'pr_details_id' =>$pd->pr_details_id,
                    'item_description' =>$pd->item_description,
                    'quantity' =>$available_qty,
                    'available_qty' =>$available_qty,
                    'uom' =>$pd->uom,
                    'unit_price' =>0,
                    'currency' =>'PHP',
                ];
            }
        }
        // $po_details = RFQOffers::select('rfq_offers.id','rfq_offers.rfq_vendor_id', 'remaining_qty', 'rfq_offers.pr_details_id','rfq_offers.offer','rfq_offers.uom','rfq_offers.unit_price','rfq_offers.currency')->join('rfq_vendor', 'rfq_vendor.id', '=', 'rfq_offers.rfq_vendor_id')->join('aoq_details', 'rfq_offers.rfq_vendor_id', '=', 'aoq_details.rfq_vendor_id')->join('aoq_head', 'aoq_details.aoq_head_id', '=', 'aoq_head.id')->where('rfq_vendor.vendor_details_id',$vendor_details_id)->where('rfq_offers.rfq_head_id',$po_head->rfq_head_id)->where('rfq_offers.awarded','=','1')->where('aoq_status','=','Awarded')->get();
        // foreach($po_details AS $pd){
        //     $balance = PrReportDetails::where('pr_details_id',$pd->pr_details_id)->where('status','!=','Cancelled')->first();
        //     $total_po=($balance->po_qty + $balance->dpo_qty + $balance->rpo_qty);
        //     $totals = $balance->pr_qty - $total_po;
        //     $total[]=$pd->unit_price * $totals;
        //     // $total[]=$pd->unit_price * $balance->pr_qty;
        //     $rfq_terms=RFQVendorTerms::where('rfq_vendor_id',$pd->rfq_vendor_id)->get();
        //     $pr_rerort_details=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->get();
        // }
        // $total_sum=array_sum($total);
        return response()->json([
            'vendor'=>$vendor,
            // 'po_head'=>$po_head,
            'pr_head'=>$pr_head,
            'po_details'=>$po_details,
            'po_no'=>$po_no,
            'currency'=>$currency,
            'dr_no'=>$dr_no,
            'vendor_terms'=>$vendor_terms,
            'prepared_by'=>Auth::user()?->name
        ],200);
    }

    public function save_direct_po(Request $request){
        $terms_list=$request->input("terms_list");
        $vendor_terms=$request->input("vendor_terms");
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
        $data_head['po_no']=$request->po_no;
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
        $data_head['method']='DPO';
        $data_head['status']=$request->status;
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
            $data_dr_head['po_no']=$request->po_no;
            $data_dr_head['pr_no']=$request->pr_no;
            $data_dr_head['site_pr']=$site_pr;
            $data_dr_head['dr_date']=date('Y-m-d');
            $data_dr_head['dr_no']=$request->dr_no;
            $data_dr_head['series']=$exp_dr[1];
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
        // $y=0;
        // $quantitys=array();
        foreach(json_decode($po_details) AS $pd){
            // if($pd->quantity != 0 ){
                // $quantity = $request->input("quantity"."$y");
                // $quantitys[] = $request->input("quantity"."$y");
                if(($request->po_head_id==0 || $request->po_head_id!=0) && $request->props_id==0 && $request->status=='Saved'){
                    $data=[
                        'po_head_id'=>$insertpohead->id,
                        'pr_details_id'=>$pd->pr_details_id,
                        'item_no'=>$x,
                        'item_description'=>$pd->item_description,
                        'quantity'=>$pd->quantity,
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> $pd->unit_price * $pd->quantity,
                        'status'=>$request->status,
                    ];
                }else if(($request->po_head_id==0 || $request->po_head_id!=0) && $request->props_id==0 && $request->status=='Draft'){
                    $data=[
                        'po_head_id'=>$insertpohead->id,
                        'pr_details_id'=>$pd->pr_details_id,
                        'item_no'=>$x,
                        'item_description'=> $pd->item_description,
                        'quantity'=> $pd->quantity,
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> $pd->unit_price * $pd->quantity,
                        'status'=>$request->status,
                    ];
                }else if($request->props_id!=0 && $request->status=='Saved'){
                    $data=[
                        'po_head_id'=>$insertpohead->id,
                        'pr_details_id'=>$pd->pr_details_id,
                        'item_no'=>$x,
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
                        'item_no'=>$x,
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
                            'to_deliver'=>$pd->quantity,
                            'quantity'=>$pd->quantity,
                        ];
                    }else if($request->po_head_id==0 && $request->status=='Draft'){
                        $data_dr_details=[
                            'po_dr_id'=>$insertdrhead->id,
                            'po_details_id'=>$po_details_id->id,
                            'pr_details_id'=>$pd->pr_details_id,
                            'to_deliver'=>$pd->quantity,
                            'quantity'=>$pd->quantity,
                        ];
                    }
                    $po_dr_items=PoDrItems::create($data_dr_details);
                }else{
                    if($request->props_id==0){
                        $po_details_id=PoDetails::updateOrCreate(
                            [
                                'po_head_id' => $insertpohead->id,
                                'pr_details_id'=>$pd->pr_details_id,
                            ],
                            [
                                'po_head_id'=>$insertpohead->id,
                                'pr_details_id'=>$pd->pr_details_id,
                                'item_no'=>$x,
                                'item_description'=>$pd->item_description,
                                'quantity'=>$pd->quantity,
                                'uom'=>$pd->uom,
                                'unit_price'=>$pd->unit_price,
                                'currency'=>$pd->currency,
                                'total_cost'=>$pd->unit_price * $pd->quantity,
                                'status'=>$request->status,
                            ]
                        );
                        $po_dr_items=PoDrItems::updateOrCreate(
                            [
                                'po_details_id'=>$po_details_id->id,
                                'pr_details_id'=>$pd->pr_details_id,
                            ],
                            [
                                'to_deliver'=>$pd->quantity,
                                'quantity'=>$pd->quantity,
                            ]
                        );
                    }else{
                        $po_details_id=PoDetails::where('id',$pd->id)->update([
                                'po_head_id'=>$insertpohead->id,
                                'pr_details_id'=>$pd->pr_details_id,
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
                        $podritems=PoDrItems::where('po_details_id',$pd->id)->where('pr_details_id',$pd->pr_details_id)->where('rfq_offer_id',0)->get();
                        foreach($podritems AS $pdi){
                            $po_dr_items=PoDrItems::where('id',$pdi->id)->update([
                                    'to_deliver'=>$pd->quantity,
                                    'quantity'=>$pd->quantity,
                                ]
                            );
                        }
                    }
                }
                if($request->po_head_id==0 && $request->props_id==0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $dpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('dpo_qty');
                    if($pr_qty > $pd->quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $pd->quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'offer'=>$pd->item_description,
                        'dpo_qty'=>$dpo_qty + $pd->quantity,
                        'status'=>$po_status
                    ]);
                }else if($request->po_head_id!=0 && $request->props_id==0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $dpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('dpo_qty');
                    if($pr_qty > $pd->quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $pd->quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'offer'=>$pd->item_description,
                        'dpo_qty'=>$dpo_qty + $pd->quantity,
                        'status'=>$po_status
                    ]);
                }else if($request->po_head_id==0 && $request->props_id!=0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $dpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('dpo_qty');
                    if($pr_qty > $pd->quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $pd->quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'offer'=>$pd->item_description,
                        'dpo_qty'=>$dpo_qty + $pd->quantity,
                        'status'=>$po_status
                    ]);
                }else if($request->po_head_id!=0 && $request->props_id!=0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $dpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('dpo_qty');
                    if($pr_qty > $pd->quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $pd->quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'offer'=>$pd->item_description,
                        'dpo_qty'=>$dpo_qty + $pd->quantity,
                        'status'=>$po_status
                    ]);
                }
                $x++;
                // $y++;
            // }
        }

        foreach(json_decode($vendor_terms) AS $vt){
            if(count(json_decode($vendor_terms))>0){
                if($request->po_head_id==0){
                    $terms = POTerms::where('po_head_id',$insertpohead->id)->where('terms', $vt->terms)->first();
                    if(is_null($terms)) {
                        $terms = new POTerms([
                            'po_head_id' => $insertpohead->id,
                            'terms' => $vt->terms
                        ]);
                        $terms->save();
                    }else{
                        $terms->terms = $vt->terms;
                        $terms->update();
                    }
                }else{
                    $terms = POTerms::where('id',$vt->id)->first();
                    $terms->terms = $vt->terms;
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

    public function delete_dpo_terms($id){
        $deleted = POTerms::find($id);
        $deleted->delete();
    }

    public function delete_dpo_instructions($id){
        $deleted = POInstruction::find($id);
        $deleted->delete();
    }

    public function dpo_viewdetails($po_head_id){
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
        $vendor_details_id= POHead::where('id',$po_head_id)->value('vendor_details_id');
        $po_vendor= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$po_head->vendor_details_id)->where('status','=','Active')->first();
        $podetails = PoDetails::where('po_head_id',$po_head_id)->where('quantity','!=','0')->where('unit_price','!=','0')->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Cancelled')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $currency=Config::get('constants.currency');
        $total=[];
        foreach($podetails AS $pd){
            $total[]=$pd->unit_price * $pd->quantity;
            $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
            $delivered_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('po_qty');
            $available_qty = $pr_qty - $delivered_qty;
            $po_details[] = [
                'pr_details_id' =>$pd->pr_details_id,
                'item_description' =>$pd->item_description,
                'quantity' =>$pd->quantity,
                'available_qty' =>$available_qty,
                'uom' =>$pd->uom,
                'unit_price' =>$pd->unit_price,
                'currency' =>$pd->currency,
            ];
        }
        $total_sum=array_sum($total);
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
        // $total=[];
        // foreach($po_details AS $pd){
        //     $total[]=$pd->unit_price * $pd->quantity;
        // }
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
            'checked_by'=>$po_head->checked_by,
            'recommended_by'=>$po_head->recommended_by,
            'approved_by'=>$po_head->approved_by,
            'cancelled_by'=>$cancelled_by,
            'grand_total'=>$total_sum,
            'vendor_details_id'=>$vendor_details_id,
        ],200);
    }

}
