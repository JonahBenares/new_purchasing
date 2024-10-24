<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RFQSeries;
use App\Models\RFQHead;
use App\Models\RFQDetails;
use App\Models\RFQOffers;
use App\Models\RFQVendor;
use App\Models\RFQVendorTerms;
use App\Models\PRHead;
use App\Models\PRDetails;
use App\Models\PrReportDetails;
use App\Models\VendorDetails;
use App\Models\VendorTerms;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Config;

class RFQController extends Controller
{
    public function get_all_rfq(Request $request){
        $all_rfq = RFQHead::orderby('rfq_no', 'DESC')->get();
        $x=0;
        $rfqarray=array();
        foreach($all_rfq AS $ar){
            $all_vendors = RFQVendor::with('vendor_details')->where('rfq_head_id',$ar->id)->orderby('vendor_name', 'ASC')->get();
            // $vendor_name=[];
            // foreach($all_vendors as $av){
            //    $vendor_name[]=$av->vendor_name;
            // }
            // $exp_vendor=implode('<br>',$vendor_name);
            $rfqarray[]=[
                'id'=>$ar->id,
                'vendor'=>$all_vendors,
                date('F j, Y', strtotime($ar->rfq_date)),
                $ar->pr_no,
                $ar->rfq_no,
                $ar->rfq_name,
                '',
                ''
            ];
            $x++;
        }
        return response()->json([
            'rfqarray'=>$rfqarray,
        ],200);
    }

    public function all_pr(){
        $prlist=PRHead::where('status','Saved')->orderBy('pr_no','ASC')->get()->unique('pr_no');
        foreach($prlist AS $pr){
            $pr_in_rfq = RFQHead::where('pr_head_id',$pr->id)->get();
            $count_pr_in_rfq=$pr_in_rfq->count();
            $pr_list[] = [
                'id'=>$pr->id,
                'pr_no'=>$pr->pr_no,
                'count_pr_in_rfq' => $count_pr_in_rfq,
            ];
        }
        return response()->json($pr_list);
    }

    public function all_vendor(){
        $vendors=VendorDetails::with('vendor_head')->where('status','Active')->orderBy('address','ASC')->get();
        foreach($vendors AS $v){
            $vendor_list[] = [
                'vendor_details_id'=>$v->id,
                'vendor_name' => $v->vendor_head->vendor_name,
                'vendor_head_id'=>$v->vendor_head_id,
                'address'=>$v->address,
                'identifier'=>$v->identifier,
            ];
        }
        return response()->json($vendor_list);
    }

    public function get_pr_data($pr_head_id){
        $curr_year = date('Y');
        $curr_mo = date('m');
        $company=Config::get('constants.company');
        if(RFQSeries::where('year', '=', $curr_year)->exists()) {
            $rfq = RFQSeries::where('year', '=', $curr_year)->max('series') + 1;
            $max_value = str_pad($rfq,4,"0",STR_PAD_LEFT);;
        } else {
            $max_value = '0001';
        }
        $rfq_no = 'RFQ-'.$curr_year.'-'.$max_value."-".$company;

        $head = PRHead::where('id',$pr_head_id)->where('status','Saved')->get();
        $userid = Auth::id();
        foreach($head AS $h){
            $PRHead = [
                'pr_head_id'=>$h->id,
                'date_prepared'=>date('F j, Y', strtotime($h->date_prepared)),
                'location'=>$h->location,
                'pr_no'=>$h->pr_no,
                'site_pr'=>$h->site_pr,
                'department_name'=>$h->department_name,
                'enduse'=>$h->enduse,
                'purpose'=>$h->purpose,
                'process_code'=>$h->process_code,
                'urgency'=>$h->urgency,
                'user_id'=>$userid,
            ];
        }

        $details = PRDetails::where('pr_head_id',$pr_head_id)->where('status','Saved')->orderBy('item_description','ASC')->get();
            foreach($details AS $d){
                $deliver_qty = PrReportDetails::where('pr_details_id',$d->id)->value('delivered_qty');
                $remaining_qty = $d->quantity - $deliver_qty;
                if($remaining_qty != 0){
                    $PRDetails[] = [
                        'checkbox'=>0,
                        'pr_details_id'=>$d->id,
                        'date_needed'=>$d->date_needed,
                        'item_description'=>$d->item_description,
                        'quantity'=>$d->quantity,
                        'remaining_qty'=>$remaining_qty,
                        'uom'=>$d->uom,
                        'pn_no'=>$d->pn_no,
                        'wh_stocks'=>$d->wh_stocks,
                    ];
                }
            }
            return response()->json([
                'PRHead'=>$PRHead,
                'PRDetails'=>$PRDetails,
                'rfq_no'=>$rfq_no,
            ],200);
        }

        public function add_rfq(Request $request){
            $rfqhead['pr_head_id']=$request->input('pr_head_id');
            $rfqhead['pr_no']=$request->input('pr_no');
            $rfqhead['rfq_name']=$request->input('rfq_name');
            $rfqhead['rfq_no']=$request->input('rfq_no');
            $rfqhead['rfq_date']=$request->input('rfq_date');
            $rfqhead['user_id']=$request->input('user_id');
            $rfqhead['created_at']=date('Y-m-d H:i:s');
            $rfq_head_id=RFQHead::insertGetId($rfqhead);

            $rfq_no = $request->input('rfq_no');
            $ser = explode("-",$rfq_no);
            $series = $ser[2];
    
            RFQSeries::create([
                'year' => date("Y"),
                'series'=>$series
            ]);

            $rfq_items = $request->input('rfq_items');
            $rfq_vendors = $request->input('rfq_vendors');
            foreach(json_decode($rfq_vendors) as $rv){
                $rfq_v['rfq_head_id']=$rfq_head_id;
                $rfq_v['pr_no']=$request->input('pr_no');
                $rfq_v['vendor_details_id']=$rv->vendor_details_id;
                $rfq_v['vendor_name']=$rv->vendor_name;
                $rfq_v['vendor_identifier']=$rv->identifier;
                $rfq_v['created_at']=date('Y-m-d H:i:s');
                $rfq_vendor_id=RFQVendor::insertGetId($rfq_v);

                $vendorterms = VendorTerms::where('vendor_details_id',$rv->vendor_details_id)->get();
                    foreach($vendorterms as $vt){
                            $new_rfq_terms['rfq_vendor_id']=$rfq_vendor_id;
                            $new_rfq_terms['terms']=$vt->terms;
                            RFQVendorTerms::create($new_rfq_terms);
                    }

                foreach(json_decode($rfq_items) as $ri){
                    $rfq_i['rfq_head_id']=$rfq_head_id;
                    $rfq_i['rfq_vendor_id']=$rfq_vendor_id;
                    $rfq_i['pr_details_id']=$ri->pr_details_id;
                    $rfq_i['pr_no']=$request->input('pr_no');
                    $rfq_i['remaining_qty']=$ri->remaining_qty;
                    $rfq_i['created_at']=date('Y-m-d H:i:s');
                    $rfq_details_id=RFQDetails::insertGetId($rfq_i);

                    $update_status = PrReportDetails::where('pr_details_id','=', $ri->pr_details_id)->update(['status' => 'For Canvass']);

                    for($x=0;$x<3;$x++){
                        RFQOffers::create([
                            'offer_no'=>$x+1,
                            'rfq_head_id'=>$rfq_head_id,
                            'rfq_vendor_id'=>$rfq_vendor_id,
                            'rfq_details_id'=>$rfq_details_id,
                            'pr_details_id'=>$ri->pr_details_id,
                            'pr_no'=>$request->input('pr_no'),
                            'remaining_qty'=>$ri->remaining_qty,
                            'uom'=>$ri->uom,
                        ]);
                    }
                }
            }
            
            return $rfq_head_id;

        }

        public function get_rfq_data($rfq_head_id, $start = 'a', $count = 26){
            $letters = [];
            $startAscii = ord($start);
    
            for ($i = 0; $i < $count; $i++) {
                $letters[] = chr($startAscii + $i);
            }

            $userid = Auth::id();
            // $vendor_terms = VendorTerms::orderBy('order_no','ASC')->get();
            $rfq_vendor_terms = RFQVendorTerms::whereIn('rfq_vendor_id',RFQVendor::where('rfq_head_id',$rfq_head_id)->pluck('id'))->orderBy('id','ASC')->get();
            $signatories=User::where('id','!=',$userid)->orderBy('name','ASC')->get()->unique('name');
            $rfqitems =PRDetails::whereNotIn('id',RFQDetails::where('rfq_head_id',$rfq_head_id)->pluck('pr_details_id'))->where('status','Saved')->get();
            $count_pritems=$rfqitems->count();
            $currency=Config::get('constants.currency');
            $rfq_head = RFQHead::with('pr_head')->where('id',$rfq_head_id)->get();

            $canvass_complete_rfq =RFQVendor::where('rfq_head_id',$rfq_head_id)->where('canvassed',1)->get();
            $count_ccr=$canvass_complete_rfq->count();
            foreach($rfq_head AS $h){
                $RFQHead = [
                    'pr_no'=>$h->pr_no,
                    'rfq_no'=>$h->rfq_no,
                    'rfq_name'=>$h->rfq_name,
                    'rfq_date'=>date('F j, Y', strtotime($h->rfq_date)),
                    'site_pr'=>$h->pr_head->site_pr,
                    'location'=>$h->pr_head->location,
                    'date_prepared'=>date('F j, Y', strtotime($h->pr_head->date_prepared)),
                    'department_name'=>$h->pr_head->department_name,
                    'enduse'=>$h->pr_head->enduse,
                    'purpose'=>$h->pr_head->purpose,
                    'process_code'=>$h->pr_head->process_code,
                    'urgency'=>$h->pr_head->urgency,
                    'preparedby_id'=>$userid,
                    'prepared_by_name'=>User::where('id',$userid)->value('name')
                ];
            }

            $rfq_vendor = RFQVendor::with('vendor_details')->where('rfq_head_id',$rfq_head_id)->orderBy('vendor_name','ASC')->get();
            $rfqvendorid=RFQVendor::where('rfq_head_id',$rfq_head_id)->first()->id;
                foreach($rfq_vendor AS $v){
                    $rfq_vendorterms = RFQVendorTerms::where('rfq_vendor_id',$v->id)->orderBy('id','ASC')->get();
                    $count_rfq_terms=$rfq_vendorterms->count();

                    $RFQVendor[] = [
                        'rfq_vendor_id'=>$v->id,
                        'vendor_details_id'=>$v->vendor_details_id,
                        'vendor_name'=>$v->vendor_name,
                        'vendor_identifier'=>$v->vendor_identifier ?? '',
                        'phone_no'=>$v->vendor_details->phone,
                        'due_date'=>$v->due_date,
                        'canvassed'=>$v->canvassed,
                        'status'=>$v->status,
                        'prepared_by_id'=>$v->prepared_by,
                        'prepared_by_name'=>User::where('id',$v->prepared_by)->value('name'),
                        'noted_by_id'=>$v->noted_by,
                        'noted_by_name'=>User::where('id',$v->noted_by)->value('name'),
                        'approved_by_id'=>$v->approved_by,
                        'approved_by_name'=>User::where('id',$v->approved_by)->value('name'),
                        'count_rfq_terms'=>$count_rfq_terms,
                    ];
                }

            $rfq_details = RFQDetails::with('pr_details')->where('rfq_head_id',$rfq_head_id)->get();
                foreach($rfq_details AS $d){
                    $RFQDetails[] = [
                        'rfq_details_id'=>$d->id,
                        'rfq_vendor_id'=>$d->rfq_vendor_id,
                        'quantity'=>$d->pr_details->quantity,
                        'remaining_qty'=>$d->remaining_qty,
                        'item_description'=>$d->pr_details->item_description,
                    ];

                $rfq_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('rfq_details_id',$d->id)->get();
                    foreach($rfq_offers AS $o){
                        $RFQOffers[] = [
                            'rfq_offer_id'=>$o->id,
                            'rfq_details_id'=>$o->rfq_details_id,
                            'offer'=>$o->offer,
                            'unit_price'=>$o->unit_price,
                            'offer_currency'=>$o->currency ?? 'PHP',
                        ];
                    }
                }

            return response()->json([
                'head'=>$RFQHead,
                'rfq_vendor'=>$RFQVendor,
                'rfq_details'=>$RFQDetails,
                'rfq_offers'=>$RFQOffers,
                'rfq_vendor_terms'=>$rfq_vendor_terms,
                // 'vendor_terms'=>$vendor_terms,
                'signatories'=>$signatories,
                'count_pritems'=>$count_pritems,
                'currency'=>$currency,
                'letters'=>$letters,
                'count_ccr'=>$count_ccr,
                'rfqvendor_id'=>$rfqvendorid,
            ],200);
        }

        public function vendor_list_data($rfq_head_id){
            $vendor_list =VendorDetails::with('vendor_head')->whereNotIn('id',RFQVendor::where('rfq_head_id',$rfq_head_id)->pluck('vendor_details_id'))->where('status','Active')->orderBy('address','ASC')->get();
            return response()->json($vendor_list);
        }

        public function add_additional_vendor(Request $request){
            $rfq_head_id = $request->input('rfq_head_id');

            $rfq_add_vendor['rfq_head_id']=$rfq_head_id;
            $rfq_add_vendor['pr_no']=$request->input('pr_no');
            $rfq_add_vendor['vendor_details_id']=$request->input('vendor_details_id');
            $rfq_add_vendor['vendor_name']=$request->input('vendor_name');
            $rfq_add_vendor['vendor_identifier']=$request->input('vendor_identifier');
            $rfq_add_vendor['due_date']=$request->input('due_date');
            $rfq_add_vendor['created_at']=date('Y-m-d H:i:s');
            $rfq_vendor_id=RFQVendor::insertGetId($rfq_add_vendor);

            $vendorterms = VendorTerms::where('vendor_details_id',$request->input('vendor_details_id'))->get();
            foreach($vendorterms as $vt){
                    $new_rfq_terms['rfq_vendor_id']=$rfq_vendor_id;
                    $new_rfq_terms['terms']=$vt->terms;
                    RFQVendorTerms::create($new_rfq_terms);
            }
            
            $rfq_details = RFQDetails::with('pr_details')->where('rfq_head_id',$rfq_head_id)->where('status','!=','Cancelled')->get()->unique('pr_details_id');
            foreach($rfq_details AS $d){
                $deliver_qty = PrReportDetails::where('pr_details_id',$d->pr_details_id)->value('delivered_qty');
                $remaining_qty = $d->pr_details->quantity - $deliver_qty;

                $rfq_i['rfq_head_id']=$rfq_head_id;
                $rfq_i['rfq_vendor_id']=$rfq_vendor_id;
                $rfq_i['pr_details_id']=$d->pr_details_id;
                $rfq_i['pr_no']=$request->input('pr_no');
                $rfq_i['remaining_qty']=$remaining_qty;
                $rfq_i['created_at']=date('Y-m-d H:i:s');
                $rfq_details_id=RFQDetails::insertGetId($rfq_i);

                
                for($x=0;$x<3;$x++){
                    RFQOffers::create([
                        'offer_no'=>$x+1,
                        'rfq_head_id'=>$rfq_head_id,
                        'rfq_vendor_id'=>$rfq_vendor_id,
                        'rfq_details_id'=>$rfq_details_id,
                        'pr_details_id'=>$d->pr_details_id,
                        'pr_no'=>$request->input('pr_no'),
                        'remaining_qty'=>$remaining_qty,
                        'uom'=>$d->uom,
                    ]);
                }
            }
        }

        public function item_list_data($rfq_head_id){
            $pr_head_id = RFQHead::where('id',$rfq_head_id)->value('pr_head_id');
            $pritem_list =PRDetails::where('pr_head_id', $pr_head_id)->whereNotIn('id',RFQDetails::where('rfq_head_id',$rfq_head_id)->pluck('pr_details_id'))->where('status','Saved')->orderBy('item_description','ASC')->get();
            $pr_item_list=array();
                foreach($pritem_list AS $pri){
                    $deliver_qty = PrReportDetails::where('pr_details_id',$pri->id)->value('delivered_qty');
                    $remaining_qty = $pri->quantity - $deliver_qty;
                    if($remaining_qty != 0){
                        $pr_item_list[] = [
                            'checkbox'=>0,
                            'pr_details_id'=>$pri->id,
                            'date_needed'=>$pri->date_needed,
                            'item_description'=>$pri->item_description,
                            'quantity'=>$pri->quantity,
                            'remaining_qty'=>$remaining_qty,
                            'uom'=>$pri->uom,
                            'pn_no'=>$pri->pn_no,
                            'wh_stocks'=>$pri->wh_stocks,
                            // 'first_offer'=>'',
                            // 'second_offer'=>'',
                            // 'third_offer'=>'',
                            // 'first_offer_up'=>'',
                            // 'second_offer_up'=>'',
                            // 'third_offer_up'=>'',
                            // 'first_offer_currency'=>'',
                            // 'second_offer_currency'=>'',
                            // 'third_offer_currency'=>'',
                        ];
                    }
                }
            return response()->json($pr_item_list);
        }

        public function add_additional_items(Request $request){
        $rfq_head_id = $request->input('rfq_head_id');
        $additional_items = $request->input('additional_items');
            
        $rfq_vendors =RFQVendor::with('vendor_details')->where('rfq_head_id',$rfq_head_id)->get();
            foreach($rfq_vendors as $rv){
                foreach(json_decode($additional_items) as $ai){
                    if($ai->checkbox != 0){
                        $rfq_i['rfq_head_id']=$rfq_head_id;
                        $rfq_i['rfq_vendor_id']=$rv->id;
                        $rfq_i['pr_details_id']=$ai->pr_details_id;
                        $rfq_i['pr_no']=$request->input('pr_no');
                        $rfq_i['remaining_qty']=$ai->remaining_qty;
                        $rfq_i['created_at']=date('Y-m-d H:i:s');
                        $rfq_details_id=RFQDetails::insertGetId($rfq_i);

                        $update_status = PrReportDetails::where('pr_details_id','=', $ai->pr_details_id)->update(['status' => 'For Canvass']);
                        
                        for($x=0;$x<3;$x++){
                            RFQOffers::create([
                                'offer_no'=>$x+1,
                                'rfq_head_id'=>$rfq_head_id,
                                'rfq_vendor_id'=>$rv->id,
                                'rfq_details_id'=>$rfq_details_id,
                                'pr_details_id'=>$ai->pr_details_id,
                                'pr_no'=>$request->input('pr_no'),
                                'remaining_qty'=>$ai->remaining_qty,
                                'uom'=>$ai->uom,
                            ]);
                        }
                    }
                }
            }
        }

        public function save_print_details(Request $request){
            $rfq_vendor_id = $request->input('rfq_vendor_id');
            $update_data=RFQVendor::where('id',$rfq_vendor_id)->first();
            $update_data->update([
                'due_date'=>$request->input('due_date'),
                'prepared_by'=>$request->input('prepared_by'),
                'noted_by'=>$request->input('noted_by'),
                'approved_by'=>$request->input('approved_by'),
            ]);

            // $add_rfq_vendor_terms = $request->input('rfqvendorterms');
            // foreach(json_decode($add_rfq_vendor_terms) as $vt){
            //     if(RFQVendorTerms::where('id','=',$vt->rfq_vendor_terms_id)->exists()){
            //         $update_rfq_terms = RFQVendorTerms::find($vt->rfq_vendor_terms_id);
            //         $update_rfq_terms->terms = $vt->terms;
            //         $update_rfq_terms->save();
            //     } else {
            //         $new_rfq_terms['rfq_vendor_id']=$rfq_vendor_id;
            //         $new_rfq_terms['terms']=$vt->terms;
            //         RFQVendorTerms::create($new_rfq_terms);
            //     }
            // }
        }

        public function add_additional_terms(Request $request){
            $new_rfq_terms['rfq_vendor_id']=$request->input('rfq_vendor_id');
            $new_rfq_terms['terms']=$request->input('terms');
            RFQVendorTerms::create($new_rfq_terms);
        }

        public function update_rfq_terms(Request $request){
            $rfq_vendor_terms_id = $request->input('rfq_vendor_terms_id');
            $update_rfq_terms=RFQVendorTerms::where('id',$rfq_vendor_terms_id)->update([
                'terms'=>$request->input('terms'),
            ]);
        }

        public function remove_terms($rfq_vendor_terms_id){
            if(RFQVendorTerms::where('id','=',$rfq_vendor_terms_id)->exists()){
            $rfqterms=RFQVendorTerms::find($rfq_vendor_terms_id);
            $rfqterms->delete(); 
            }
        }

        public function canvass_complete_vendor(Request $request){
            $userid = Auth::id();
            $rfq_head_id = $request->input('rfq_head_id');
            $rfq_vendor_id = $request->input('rfq_vendor_id');
            $canvass_vendor_details = $request->input('vendor_offers');
            foreach(json_decode($canvass_vendor_details) as $dv){
                if(RFQOffers::where('id','=',$dv->rfq_offer_id)->exists()){
                    $update_vendor_offer = RFQOffers::find($dv->rfq_offer_id);
                    $update_vendor_offer->offer = $dv->offer;
                    $update_vendor_offer->unit_price = $dv->unit_price;
                    $update_vendor_offer->currency = $dv->currency;
                    $update_vendor_offer->save();
                }
            }

            // $update_status = RFQOffers::where('rfq_head_id','=', $rfq_head_id)->where('rfq_vendor_id','=', $rfq_vendor_id)->update(['status' => 'Saved']);

            $rfqvendor=RFQVendor::where('id',$rfq_vendor_id)->first();
            $data = [
                'canvassed' => "1",
                'canvassed_by' => $userid,
                'canvassed_date' => date('Y-m-d H:i:s'),
                
            ];
            $rfqvendor->update($data);

            // $add_rfq_vendor_terms = $request->input('rfqvendorterms');
            // foreach(json_decode($add_rfq_vendor_terms) as $vt){
            //     if(RFQVendorTerms::where('id','=',$vt->rfq_vendor_terms_id)->exists()){
            //         $update_rfq_terms = RFQVendorTerms::find($vt->rfq_vendor_terms_id);
            //         $update_rfq_terms->terms = $vt->terms;
            //         $update_rfq_terms->save();
            //     } else {
            //         $new_rfq_terms['rfq_vendor_id']=$rfq_vendor_id;
            //         $new_rfq_terms['terms']=$vt->terms;
            //         RFQVendorTerms::create($new_rfq_terms);
            //     }
            // }

            $update_status = RFQVendor::where('rfq_head_id','=', $rfq_head_id)->where('id','=', $rfq_vendor_id)->update(['status' => 'Saved']);
        }

        public function draft_vendor(Request $request){
            $rfq_head_id = $request->input('rfq_head_id');
            $rfq_vendor_id = $request->input('rfq_vendor_id');
            $draft_vendor_details = $request->input('vendor_offers');
            foreach(json_decode($draft_vendor_details) as $dv){
                if(RFQOffers::where('id','=',$dv->rfq_offer_id)->exists()){
                    $update_vendor_offer = RFQOffers::find($dv->rfq_offer_id);
                    $update_vendor_offer->offer = $dv->offer;
                    $update_vendor_offer->unit_price = $dv->unit_price;
                    $update_vendor_offer->currency = $dv->currency;
                    $update_vendor_offer->save();
                }
            }
            
            $update_status = RFQVendor::where('rfq_head_id','=', $rfq_head_id)->where('id','=', $rfq_vendor_id)->update(['status' => 'Draft']);

            // $add_rfq_vendor_terms = $request->input('rfqvendorterms');
            // foreach(json_decode($add_rfq_vendor_terms) as $vt){
            //     if(RFQVendorTerms::where('id','=',$vt->rfq_vendor_terms_id)->exists()){
            //         $update_rfq_terms = RFQVendorTerms::find($vt->rfq_vendor_terms_id);
            //         $update_rfq_terms->terms = $vt->terms;
            //         $update_rfq_terms->save();
            //     } else {
            //         $new_rfq_terms['rfq_vendor_id']=$rfq_vendor_id;
            //         $new_rfq_terms['terms']=$vt->terms;
            //         RFQVendorTerms::create($new_rfq_terms);
            //     }
            // }
        }
}
