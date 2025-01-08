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
use App\Models\VendorDetails;
use App\Models\JOAOQHead;
use App\Models\JORFQTerms;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Config;

class JOIDirectController extends Controller
{
    public function get_direct_jorno(){
        $jorno_dropdown=JORHead::where('status','Saved')->get();
        return response()->json([
            'jorno_dropdown'=>$jorno_dropdown,
        ],200);
    }

    public function djo_supplier_dropdown(){
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

    public function generate_direct_joi($jor_no,$vendor_details_id){
        $currency=Config::get('constants.currency');
        $joaoq_head_id_exp=explode('+',$jor_no);
        $jor_no_exp=$joaoq_head_id_exp[0];
        $jor_head_id=$joaoq_head_id_exp[1];
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
        // $joihead= JOIHead::where('jor_no',$jor_no_exp)->first();
        $joi_head = [
            'status' =>'',
        ];
        $jor_head= JORHead::where('jor_no',$jor_no_exp)->first();
        $vendor= VendorDetails::select('vendor_details.id','identifier','vendor_name','fax','phone','contact_person','address')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('vendor_details.id',$vendor_details_id)->where('status','=','Active')->first();
        // $joi_labor_details = JORFQLaborOffers::with('jor_labor_details')->select('jo_rfq_labor_offer.id','jo_rfq_labor_offer.jo_rfq_vendor_id', 'jo_rfq_labor_offer.jor_labor_details_id','jo_rfq_labor_offer.offer','jo_rfq_labor_offer.uom','jo_rfq_labor_offer.unit_price','jo_rfq_labor_offer.currency')->join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_labor_offer.jo_rfq_vendor_id')->join('jo_aoq_details', 'jo_rfq_labor_offer.jo_rfq_vendor_id', '=', 'jo_aoq_details.jo_rfq_vendor_id')->join('jo_aoq_head', 'jo_aoq_details.jo_aoq_head_id', '=', 'jo_aoq_head.id')->where('jo_rfq_vendor.vendor_details_id',$vendor_details_id)->where('jo_rfq_labor_offer.jo_rfq_head_id',$joi_head->jo_rfq_head_id)->where('jo_rfq_labor_offer.awarded','=','1')->where('aoq_status','=','Awarded')->get();
        $joilabordetails = JORLaborDetails::where('jor_head_id',$jor_head_id)->get();
        $joi_labor_details=array();
        foreach($joilabordetails AS $jld){
            $sum_joi_labor=JOILaborDetails::where('jor_labor_details_id',$jld->id)->where('jo_rfq_labor_offer_id',0)->where('status','Saved')->sum('quantity');
            $deduct = $jld->quantity - $sum_joi_labor;
            $total_labor[]=$jld->unit_cost * $deduct;
            // $total[]=$jld->unit_price * $balance->pr_qty;
            $jo_rfq_terms=JORFQTerms::where('jo_rfq_vendor_id',$jld->jo_rfq_vendor_id)->get();

            $joi_labor_details[] = [
                'offer' =>$jld->scope_of_work,
                'jor_labor_details_id' =>$jld->id,
                'jo_rfq_labor_offer_id' =>0,
                'quantity' =>$jld->quantity,
                'uom' =>$jld->uom,
                'unit_price' =>$jld->unit_cost,
                'currency' =>'PHP',
            ];
        }
        
        // $joi_material_details = JORFQMaterialOffers::with('jor_material_details')->select('jo_rfq_material_offer.id','jo_rfq_material_offer.jo_rfq_vendor_id'   , 'jo_rfq_material_offer.jor_material_details_id','jo_rfq_material_offer.offer','jo_rfq_material_offer.uom','jo_rfq_material_offer.unit_price','jo_rfq_material_offer.currency')->join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_material_offer.jo_rfq_vendor_id')->join('jo_aoq_details', 'jo_rfq_material_offer.jo_rfq_vendor_id', '=', 'jo_aoq_details.jo_rfq_vendor_id')->join('jo_aoq_head', 'jo_aoq_details.jo_aoq_head_id', '=', 'jo_aoq_head.id')->where('jo_rfq_vendor.vendor_details_id',$vendor_details_id)->where('jo_rfq_material_offer.jo_rfq_head_id',$joi_head->jo_rfq_head_id)->where('jo_rfq_material_offer.awarded','=','1')->where('aoq_status','=','Awarded')->get();
        $joimaterialdetails = JORMaterialDetails::where('jor_head_id',$jor_head_id)->get();
        $joi_material_details=array();
        foreach($joimaterialdetails AS $jmd){
            // $sum_joi_material=JOIMaterialDetails::where('jor_material_details_id',$jmd->id)->where('jo_rfq_material_offer_id',0)->where('status','Saved')->sum('quantity');
            // $deducted = $jmd->quantity - $sum_joi_material;
            // $total_material[]=$jmd->unit_price * $deducted;

            $joi_material_details[] = [
                'offer' =>$jmd->item_description,
                'jor_material_details_id' =>$jmd->id,
                'jo_rfq_material_offer_id' =>0,
                'quantity' =>$jmd->quantity,
                'uom' =>$jmd->uom,
                'unit_price' =>0,
                'currency' =>'PHP',
            ];
        }
        $total_labor_sum=array_sum($total_labor);
        // $total_material_sum=array_sum($total_material);
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
            'grand_material_total'=>0,
            'currency'=>$currency,
            'prepared_by'=>Auth::user()?->name
        ],200);
    }

    public function save_direct_joi(Request $request){
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
        $data_head['method']='DJO';
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
                        'jo_rfq_labor_offer_id'=>$pd->jo_rfq_labor_offer_id,
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
                        'jo_rfq_labor_offer_id'=>$pd->jo_rfq_labor_offer_id,
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
                        'jo_rfq_labor_offer_id'=>$pd->jo_rfq_labor_offer_id,
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
                        'jo_rfq_labor_offer_id'=>$pd->jo_rfq_labor_offer_id,
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
                                'jo_rfq_labor_offer_id'=>$pd->jo_rfq_labor_offer_id,
                                'to_deliver'=>$quantity_labor,
                                'quantity'=>$quantity_labor,
                            ];
                        }else if($request->joi_head_id==0 && $request->status=='Draft'){
                            $data_dr_details=[
                                'joi_dr_id'=>$insertdrhead->id,
                                'joi_labor_details_id'=>$joi_labor_details_id->id,
                                'jor_labor_details_id'=>$pd->jor_labor_details_id,
                                'jo_rfq_labor_offer_id'=>$pd->jo_rfq_labor_offer_id,
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
                                    'jo_rfq_labor_offer_id'=>$pd->jo_rfq_labor_offer_id,
                                    'jor_labor_details_id'=>$pd->jor_labor_details_id,
                                ],
                                [
                                    'joi_head_id'=>$insertjoihead->id,
                                    'jor_labor_details_id'=>$pd->jor_labor_details_id,
                                    'jo_rfq_labor_offer_id'=>$pd->jo_rfq_labor_offer_id,
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
                                    'jo_rfq_labor_offer_id'=>$pd->jo_rfq_labor_offer_id,
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
                        'jo_rfq_material_offer_id'=>$jmd->jo_rfq_material_offer_id,
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
                        'jo_rfq_material_offer_id'=>$jmd->jo_rfq_material_offer_id,
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
                        'jo_rfq_material_offer_id'=>$jmd->jo_rfq_material_offer_id,
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
                        'jo_rfq_material_offer_id'=>$jmd->jo_rfq_material_offer_id,
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
                                'jo_rfq_material_offer_id'=>$jmd->jo_rfq_material_offer_id,
                                'to_deliver'=>$quantity_material,
                                'quantity'=>$quantity_material,
                            ];
                        }else if($request->joi_head_id==0 && $request->status=='Draft'){
                            $data_dr_details=[
                                'joi_dr_id'=>$insertdrhead->id,
                                'joi_material_details_id'=>$joi_material_details_id->id,
                                'jor_material_details_id'=>$jmd->jor_material_details_id,
                                'jo_rfq_material_offer_id'=>$jmd->jo_rfq_material_offer_id,
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
                                    'jo_rfq_material_offer_id'=>$jmd->jo_rfq_material_offer_id,
                                    'jor_material_details_id'=>$jmd->jor_material_details_id,
                                ],
                                [
                                    'joi_head_id'=>$insertjoihead->id,
                                    'jor_material_details_id'=>$jmd->jor_material_details_id,
                                    'jo_rfq_material_offer_id'=>$jmd->jo_rfq_material_offer_id,
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
                                    'jo_rfq_material_offer_id'=>$jmd->jo_rfq_material_offer_id,
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
}
