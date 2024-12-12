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

class RepeatOrderPOController extends Controller
{
    public function get_repeat_pr(){
        $available_pr=PRHead::where('status', 'Saved')->get();
        $prno_dropdown=array();
        foreach($available_pr AS $apr){
            $count_available_pr = PrReportDetails::where('pr_no',$apr->pr_no)->whereColumn('pr_qty','!=','delivered_qty')->where('status','!=','Cancelled')->get();
            $count_pr=$count_available_pr->count();
            if($count_pr != 0){
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

    public function repeat_supplier_dropdown(){
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

    public function generate_rpo($pr_no,$vendor_details_id){
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

        $pr_details = PrReportDetails::where('pr_no',$pr_no)->whereColumn('pr_qty','!=','delivered_qty')->where('status','!=','Cancelled')->get();
        $currency=Config::get('constants.currency');
        foreach($pr_details AS $pd){
            $available_qty = $pd->pr_qty - $pd->delivered_qty;
            $po_details[] = [
                'pr_details_id' =>$pd->pr_details_id,
                'item_description' =>$pd->item_description,
                'quantity' =>$available_qty,
                'available_qty' =>$available_qty,
                'uom' =>$pd->uom,
                'unit_price' =>0,
                'currency' =>'',
                'offer_desc' =>'',
                'reference_po_no' =>'',
                'reference_po_details_id' =>0,
                'totalprice' =>$pd->total_cost,
            ];
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

    public function get_po_items($item_desc,$vendor_details_id){
        // $poitems=PODetails::with('po_head')->where('item_description', 'LIKE', '%' . $item_desc . '%')->where('unit_price','!=',0)->get();
        $query = PODetails::with('po_head')->where('item_description', 'LIKE', '%' . $item_desc . '%')->where('unit_price','!=',0);
        $query->whereHas('po_head', function ($query) use($vendor_details_id) {
            $query->where('vendor_details_id', '=', $vendor_details_id);
            $query->where('status', '=', 'Saved');
        });
        $poitems = $query->get();

        $po_items=array();
        foreach($poitems AS $pi){
                $po_items[] = [
                    'po_details_id'=>$pi->id,
                    'po_no'=>$pi->po_head->po_no,
                    'item_description'=>$pi->item_description,
                    'unit_price'=>$pi->unit_price,
                    'offer_currency'=>$pi->currency,
                ];
        }
        return response()->json([
            'po_items'=>$po_items,
        ],200);
    }

    public function save_repeat_po(Request $request){
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
        $data_head['method']='RPO';
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
                        'reference_po_no'=>$pd->reference_po_no,
                        'reference_po_details_id'=>$pd->reference_po_details_id,
                        'item_description'=>$pd->offer_desc,
                        'quantity'=>$pd->quantity,
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> (float)$pd->unit_price * (float)$pd->quantity,
                        'status'=>$request->status,
                    ];
                }else if(($request->po_head_id==0 || $request->po_head_id!=0) && $request->props_id==0 && $request->status=='Draft'){
                    $data=[
                        'po_head_id'=>$insertpohead->id,
                        'pr_details_id'=>$pd->pr_details_id,
                        'item_no'=>$x,
                        'reference_po_no'=>$pd->reference_po_no,
                        'reference_po_details_id'=>$pd->reference_po_details_id,
                        'item_description'=> $pd->offer_desc,
                        'quantity'=> $pd->quantity,
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> (float)$pd->unit_price * (float)$pd->quantity,
                        'status'=>$request->status,
                    ];
                }else if($request->props_id!=0 && $request->status=='Saved'){
                    $data=[
                        'po_head_id'=>$insertpohead->id,
                        'pr_details_id'=>$pd->pr_details_id,
                        'item_no'=>$x,
                        'reference_po_no'=>$pd->reference_po_no,
                        'reference_po_details_id'=>$pd->reference_po_details_id,
                        'item_description'=> $pd->offer_desc,
                        'quantity'=> $pd->quantity,
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> (float)$pd->unit_price * (float)$pd->quantity,
                        'status'=>$request->status,
                    ];
                }else if($request->props_id!=0 && $request->status=='Draft'){
                    $data=[
                        'po_head_id'=>$insertpohead->id,
                        'pr_details_id'=>$pd->pr_details_id,
                        'item_no'=>$x,
                        'reference_po_no'=>$pd->reference_po_no,
                        'reference_po_details_id'=>$pd->reference_po_details_id,
                        'item_description'=> $pd->offer_desc,
                        'quantity'=> $pd->quantity,
                        'uom'=>$pd->uom,
                        'unit_price'=>$pd->unit_price,
                        'currency'=>$pd->currency,
                        'total_cost'=> (float)$pd->unit_price * (float)$pd->quantity,
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
                                'reference_po_no'=>$pd->reference_po_no,
                                'reference_po_details_id'=>$pd->reference_po_details_id,
                                'item_description'=>$pd->offer_desc,
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
                                'reference_po_no'=>$pd->reference_po_no,
                                'reference_po_details_id'=>$pd->reference_po_details_id,
                                'item_description'=>$pd->offer_desc,
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
                    $rpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('rpo_qty');
                    if($pr_qty > $pd->quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $pd->quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'offer'=>$pd->offer_desc,
                        'rpo_qty'=>$rpo_qty + $pd->quantity,
                        'status'=>$po_status
                    ]);
                }else if($request->po_head_id!=0 && $request->props_id==0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $rpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('rpo_qty');
                    if($pr_qty > $pd->quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $pd->quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'offer'=>$pd->offer_desc,
                        'rpo_qty'=>$rpo_qty + $pd->quantity,
                        'status'=>$po_status
                    ]);
                }else if($request->po_head_id==0 && $request->props_id!=0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $rpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('rpo_qty');
                    if($pr_qty > $pd->quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $pd->quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'offer'=>$pd->offer_desc,
                        'rpo_qty'=>$rpo_qty + $pd->quantity,
                        'status'=>$po_status
                    ]);
                }else if($request->po_head_id!=0 && $request->props_id!=0 && $request->status=='Saved'){
                    $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
                    $rpo_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('rpo_qty');
                    if($pr_qty > $pd->quantity){
                        $po_status='PO Issued Partially';
                    }else if($pr_qty == $pd->quantity){
                        $po_status='PO Issued Fully';
                    }
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->update([
                        'offer'=>$pd->offer_desc,
                        'rpo_qty'=>$rpo_qty + $pd->quantity,
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

    public function rpo_viewdetails($po_head_id){
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
        $podetails = PoDetails::where('po_head_id',$po_head_id)->where('quantity','!=','0')->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Cancelled')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        $currency=Config::get('constants.currency');
        $total=[];
        foreach($podetails AS $pd){
            $total[]=$pd->unit_price * $pd->quantity;
            $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
            $delivered_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('po_qty');
            $pr_item=PRDetails::where('id',$pd->pr_details_id)->value('item_description');
            $available_qty = $pr_qty - $delivered_qty;
            $po_details[] = [
                'id' =>$pd->id,
                'item_no' =>$pd->item_no,
                'po_head_id' =>$pd->po_head_id,
                'pr_details_id' =>$pd->pr_details_id,
                'item_description' =>$pr_item,
                'offer_desc' =>$pd->item_description,
                'quantity' =>$pd->quantity,
                'available_qty' =>$available_qty,
                'uom' =>$pd->uom,
                'unit_price' =>$pd->unit_price,
                'currency' =>$pd->currency,
                'reference_po_no' =>$pd->reference_po_no,
                'reference_po_details_id' =>$pd->reference_po_details_id,
                'totalprice' =>$pd->total_cost,
                'cancelled_date'=>$pd->cancelled_date,
                'cancelled_by'=>$pd->cancelled_by,
                'cancelled_reason'=>$pd->cancelled_reason,
                'status'=>$pd->status,
            ];
        }
        $total_sum=array_sum($total);
        $podetailsview = PoDetails::where('po_head_id',$po_head_id)->where('quantity','!=','0')->where(function ($q) {
            $q->where('status','Saved')->Orwhere('status','Draft')->Orwhere('status','Revised');
        })->get();
        foreach($podetailsview AS $pd){
            $total[]=$pd->unit_price * $pd->quantity;
            $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
            $delivered_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('po_qty');
            $pr_item=PRDetails::where('id',$pd->pr_details_id)->value('item_description');
            $available_qty = $pr_qty - $delivered_qty;
            $po_details_view[] = [
                'id' =>$pd->id,
                'item_no' =>$pd->item_no,
                'po_head_id' =>$pd->po_head_id,
                'pr_details_id' =>$pd->pr_details_id,
                'item_description' =>$pr_item,
                'offer_desc' =>$pd->item_description,
                'quantity' =>$pd->quantity,
                'available_qty' =>$available_qty,
                'uom' =>$pd->uom,
                'unit_price' =>$pd->unit_price,
                'currency' =>$pd->currency,
                'reference_po_no' =>$pd->reference_po_no,
                'reference_po_details_id' =>$pd->reference_po_details_id,
                'totalprice' =>$pd->total_cost,
                'cancelled_date'=>$pd->cancelled_date,
                'cancelled_by'=>$pd->cancelled_by,
                'cancelled_reason'=>$pd->cancelled_reason,
                'status'=>$pd->status,
            ];
        }
        
        $podetailstemp = PoDetailsTemp::where('po_head_id',$po_head_id)->get();
        $po_details_temp = [];
        foreach($podetailstemp AS $pd){
            $total[]=$pd->unit_price * $pd->quantity;
            $pr_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('pr_qty');
            $delivered_qty=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->value('po_qty');
            $pr_item=PRDetails::where('id',$pd->pr_details_id)->value('item_description');
            $available_qty = $pr_qty - $delivered_qty;
            $po_details_temp[] = [
                'id' =>$pd->id,
                'po_head_id' =>$pd->po_head_id,
                'pr_details_id' =>$pd->pr_details_id,
                'item_description' =>$pr_item,
                'offer_desc' =>$pd->item_description,
                'quantity' =>$pd->quantity,
                'available_qty' =>$available_qty,
                'uom' =>$pd->uom,
                'unit_price' =>$pd->unit_price,
                'currency' =>$pd->currency,
                'reference_po_no' =>$pd->reference_po_no,
                'reference_po_details_id' =>$pd->reference_po_details_id,
                'totalprice' =>$pd->total_cost,
            ];
        }

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

    public function delete_rpo_terms($id){
        $deleted = POTerms::find($id);
        $deleted->delete();
    }

    public function delete_rpo_instructions($id){
        $deleted = POInstruction::find($id);
        $deleted->delete();
    }

    public function save_change_rpo(Request $request){
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
                // 'rfq_offers_id'=>$pd->rfq_offers_id,
                'reference_po_details_id'=>$pd->reference_po_details_id,
                'reference_po_no'=>$pd->reference_po_no,
                'item_description'=>$pd->offer_desc,
                // 'quantity'=>$pd->quantity,
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

    public function save_approved_repeat_revision(Request $request){
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
                // 'rfq_offers_id'=>$pd->rfq_offers_id,
                'reference_po_details_id'=>$pd->reference_po_details_id,
                'reference_po_no'=>$pd->reference_po_no,
                'item_description'=>$pd->offer_desc,
                'quantity'=>$pd->quantity,
                'uom'=>$pd->uom,
                'unit_price'=>$pd->unit_price,
                'total_cost'=>$pd->totalprice,
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
                }else if($method=='DPO'){
                    $difference = $pd->quantity-$dpo_qty;
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->first();
                    $update_prreport->dpo_qty += $difference;
                    $update_prreport->status = $po_status;
                    $update_prreport->update();
                }else if($method=='RPO'){
                    $difference = $pd->quantity-$rpo_qty;
                    $update_prreport=PrReportDetails::where('pr_details_id',$pd->pr_details_id)->first();
                    $update_prreport->rpo_qty += $difference;
                    $update_prreport->status = $po_status;
                    $update_prreport->update();
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
}
