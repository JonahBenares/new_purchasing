<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AOQSeries;
use App\Models\AOQHead;
use App\Models\AOQDetails;
use App\Models\RFQHead;
use App\Models\RFQDetails;
use App\Models\RFQOffers;
use App\Models\RFQVendor;
use App\Models\RFQVendorTerms;
use App\Models\PRHead;
use App\Models\PRDetails;
use App\Models\VendorDetails;
use App\Models\PrReportDetails;
use App\Exports\AOQExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Config;

class AOQController extends Controller
{
    public function get_all_aoq(Request $request){
        $all_aoq = AOQHead::orderby('aoq_date', 'DESC')->get();
        $x=0;
        $aoqarray=array();
        foreach($all_aoq AS $aa){
            $aoq_details_id = AOQDetails::where('aoq_head_id',$aa->id)->orderBy('id', 'ASC')->value('id');
            $pr_head_id  = RFQHead::where('id',$aa->rfq_head_id)->value('pr_head_id');
            $department_name= PRHead::where('id',$pr_head_id)->value('department_name');
            $enduse= PRHead::where('id',$pr_head_id)->value('enduse');
            $purpose= PRHead::where('id',$pr_head_id)->value('purpose');
            $requestor= PRHead::where('id',$pr_head_id)->value('requestor');
            $allvendors = RFQVendor::with('vendor_details')->whereIn('id',AOQDetails::where('aoq_head_id',$aa->id)->pluck('rfq_vendor_id'))->orderby('vendor_name', 'ASC')->get();
            $all_vendors=[];
            foreach($allvendors AS $av){
                $awarded_vendor = RFQOffers::where('rfq_vendor_id',$av->id)->where('awarded',1)->get();
                $count_awarded_vendor=$awarded_vendor->count();
                $all_vendors[] = [
                    'vendor_name'=>$av->vendor_name,
                    'identifier'=>$av->vendor_identifier,
                    'count_awarded'=>$count_awarded_vendor,
                ];
            }

            $aoqarray[]=[
                'id'=>$aa->id,
                'status'=>$aa->status,
                'aoq_status'=>$aa->aoq_status,
                'aoq_details_id'=>$aoq_details_id,
                'vendor'=>$all_vendors,
                date('F j, Y', strtotime($aa->aoq_date)),
                $aa->pr_no,
                '',
                $department_name,
                $enduse,
                $requestor,
                '',
                ''
            ];
            $x++;
        }
        return response()->json([
            'aoqarray'=>$aoqarray,
        ],200);
    }

    public function all_rfq_pr(){
        $prlist=RFQVendor::where('canvassed',1)->orderBy('pr_no','ASC')->get()->unique('pr_no');
        foreach($prlist AS $pr){
            // $saved_pr_rfq = RFQVendor::where('rfq_head_id',$pr->id)->get();
            // $count_saved_pr=$saved_pr_rfq->count();
            // if($count_saved_pr != 0){
                $pr_list[] = [
                    'rfq_head_id'=>$pr->rfq_head_id,
                    'pr_no'=>$pr->pr_no,
                ];
            // }
        }
        return response()->json($pr_list);
    }

    public function all_rfq($pr_no){
        $rfqlist=RFQVendor::with('rfq_head')->where('pr_no',$pr_no)->where('canvassed',1)->orderBy('pr_no','ASC')->get()->unique('rfq_head_id');
        $rfq_list = [];
        foreach($rfqlist AS $rfq){
            $rfq_aoq = AOQHead::where('rfq_head_id',$rfq->rfq_head_id)->get();
            $count_saved_rfq=$rfq_aoq->count();
                $rfq_list[] = [
                    'rfq_no'=>$rfq->rfq_head->rfq_no,
                    'rfq_head_id'=>$rfq->rfq_head_id,
                    'count_aoq'=>$count_saved_rfq,
                ];
        }
        return response()->json($rfq_list);
    }

    public function create_new_aoq($rfq_head_id){
        $userid = Auth::id();
        $curr_year = date('Y');
        $company=Config::get('constants.company');
        if(AOQSeries::where('year', '=', $curr_year)->exists()) {
            $aoq = AOQSeries::where('year', '=', $curr_year)->max('series') + 1;
            $max_value = str_pad($aoq,4,"0",STR_PAD_LEFT);;
        } else {
            $max_value = '0001';
        }
        $aoq_no = 'AOQ-'.$curr_year.'-'.$max_value."-".$company;

        $aoq_head = RFQHead::with('pr_head')->where('id',$rfq_head_id)->get();
        foreach($aoq_head AS $ah){
            $head_data = [
                'aoq_date'=>date("Y-m-d"),
                'rfq_no'=>$ah->rfq_no,
                'rfq_head_id'=>$ah->id,
                'pr_no'=>$ah->pr_no,
                'department'=>$ah->pr_head->department_name,
                'enduse'=>$ah->pr_head->enduse,
                'purpose'=>$ah->pr_head->purpose,
                'requestor'=>$ah->pr_head->requestor,
                'requestor_id'=>$ah->pr_head->requestor_id,
                'prepared_by'=>$userid,
                'prepared_by_name'=>User::where('id',$userid)->value('name'),
            ];
        }

        $rfqvendor = RFQVendor::where('rfq_head_id',$rfq_head_id)->where('canvassed',1)->orderBy('vendor_name','ASC')->get();
        $rfq_vendor = [];
        foreach($rfqvendor AS $rv){
            $rfq_vendor[] = [
                'vendor_checkbox'=>0,
                'rfq_vendor_id'=>$rv->id,
                'vendor_name'=>$rv->vendor_name,
                'vendor_identifier'=>$rv->vendor_identifier,
            ];
        }

        $signatories=User::where('id','!=',$userid)->orderBy('name','ASC')->get()->unique('name');

        return response()->json([
            'aoq_no'=>$aoq_no,
            'aoq_head_data'=>$head_data,
            'rfq_vendor'=>$rfq_vendor,
            'signatories'=>$signatories,
        ],200);
    }

    public function add_aoq_head(Request $request){
        $aoqhead['aoq_no']=$request->input('aoq_no');
        $aoqhead['rfq_head_id']=$request->input('rfq_head_id');
        $aoqhead['pr_no']=$request->input('pr_no');
        $aoqhead['aoq_date']=$request->input('aoq_date');
        $aoqhead['date_needed']=$request->input('date_needed');
        $aoqhead['prepared_by']=$request->input('prepared_by');
        $aoqhead['received_by']=$request->input('received_by');
        $aoqhead['award_recommended_by']=$request->input('award_recommended_by') ?? 0;
        $aoqhead['recommended_by']=$request->input('recommended_by');
        $aoqhead['approved_by']=$request->input('approved_by');
        $aoqhead['user_id']=$request->input('prepared_by');
        $aoqhead['aoq_status']='For TE';
        $aoqhead['created_at']=date('Y-m-d H:i:s');
        $aoq_head_id=AOQHead::insertGetId($aoqhead);

        $aoq_no = $request->input('aoq_no');
        $ser = explode("-",$aoq_no);
        $series = $ser[2];

        AOQSeries::create([
            'year' => date("Y"),
            'series'=>$series
        ]);

        $aoq_vendors = $request->input('aoq_vendors');
        foreach(json_decode($aoq_vendors) as $av){
            if($av->vendor_checkbox != 0){
                $aoq_v['aoq_head_id']=$aoq_head_id;
                $aoq_v['rfq_vendor_id']=$av->rfq_vendor_id;
                $aoq_v['created_at']=date('Y-m-d H:i:s');
                AOQDetails::create($aoq_v);
            }


            $update_pr_status = PrReportDetails::whereIn('pr_details_id',RFQDetails::where('rfq_vendor_id',$av->rfq_vendor_id)->pluck('pr_details_id'))->update(['status' => 'For TE']);
        }

        return $aoq_head_id;
    }

    public function aoq_head_details($aoq_head_id, $start = 'a', $count = 26){
        $letters = [];
        $startAscii = ord($start);

        for ($i = 0; $i < $count; $i++) {
            $letters[] = chr($startAscii + $i);
        }
        $userid = Auth::id();
        $currency=Config::get('constants.currency');
        $rfq_head_id = AOQHead::where('id',$aoq_head_id)->value('rfq_head_id');
        $all_terms =RFQVendorTerms::whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->orderBy('id','ASC')->get();
        $aoq_details_id = AOQDetails::where('aoq_head_id',$aoq_head_id)->orderBy('id', 'ASC')->value('id');
        $aoq_head = AOQHead::where('id',$aoq_head_id)->get();
        foreach($aoq_head AS $ah){
            $head_data = [
                'aoq_head_id'=>$ah->id,
                'aoq_no'=>$ah->aoq_no,
                'status'=>$ah->status,
                'aoq_status'=>$ah->aoq_status,
                'aoq_date'=>date('F j, Y', strtotime($ah->aoq_date)),
                'rfq_no'=>$ah->rfq_no,
                'rfq_head_id'=>$rfq_head_id,
                'pr_no'=>$ah->pr_no,
                'department'=>PRHead::where('pr_no',$ah->pr_no)->value('department_name'),
                'enduse'=>PRHead::where('pr_no',$ah->pr_no)->value('enduse'),
                'purpose'=>PRHead::where('pr_no',$ah->pr_no)->value('purpose'),
                'requestor'=>PRHead::where('pr_no',$ah->pr_no)->value('requestor'),
                'date_needed'=>date('F j, Y', strtotime($ah->date_needed)),
                'prepared_by_name'=>User::where('id',$ah->prepared_by)->value('name'),
                'received_by_name'=>User::where('id',$ah->received_by)->value('name'),
                'award_recommended_by_name'=>User::where('id',$ah->award_recommended_by)->value('name'),
                'recommended_by_name'=>User::where('id',$ah->recommended_by)->value('name'),
                'approved_by_name'=>User::where('id',$ah->approved_by)->value('name'),
            ];
        }

        $rfq_vendors = RFQVendor::where('rfq_head_id',$rfq_head_id)->whereNotIn('id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->where('canvassed',1)->orderby('vendor_name', 'ASC')->get();
        $count_rfq_vendors =$rfq_vendors->count();

        $aoq_details = AOQDetails::with('rfq_vendor')->where('aoq_head_id',$aoq_head_id)->get();
        foreach($aoq_details AS $ad){
            // $min_price = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('pr_details_id',$ad->pr_details_id)->min('unit_price');
            // $min_price = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('pr_details_id',$ad->rfq_vendor->pr_details_id)->min('unit_price');
            $vendor_data[] = [
                'rfq_vendor_id'=>$ad->rfq_vendor->id,
                'vendor_name'=>$ad->rfq_vendor->vendor_name,
                'vendor_identifier'=>$ad->rfq_vendor->vendor_identifier,
                'vendor_details_id'=>$ad->rfq_vendor->vendor_details_id,
                'contact_person'=>VendorDetails::where('id',$ad->rfq_vendor->vendor_details_id)->value('contact_person'),
                'phone'=>VendorDetails::where('id',$ad->rfq_vendor->vendor_details_id)->value('phone'),
            ];

            
            $vendor_terms = RFQVendorTerms::where('rfq_vendor_id',$ad->rfq_vendor_id)->get();
            foreach($vendor_terms AS $vt){
                $vendor_terms[] = [
                    'terms_id'=>$vt->id,
                    'rfq_vendor_id'=>$vt->rfq_vendor_id,
                    'terms'=>$vt->terms,
                ];
            }
        }

        $first_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->where('offer_no',1)->get();
        $second_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->where('offer_no',2)->get();
        $third_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->where('offer_no',3)->get();
        $aoq_items = RFQDetails::with('pr_details')->where('rfq_head_id',$rfq_head_id)->get()->unique('pr_details_id');
        foreach($aoq_items AS $ai){
            $min_price = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('unit_price','!=',0)->where('pr_details_id',$ai->pr_details_id)->whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->min('unit_price');
            $items_data[] = [
                'rfq_details_id'=>$ai->id,
                'pr_details_id'=>$ai->pr_details_id,
                'item_description'=>$ai->pr_details->item_description,
                'uom'=>$ai->pr_details->uom,
                'quantity'=>$ai->pr_details->quantity,
                'rfq_vendor_id'=>$ai->rfq_vendor_id,
                'min_price'=>$min_price,
                'awarded'=>$ai->awarded,
            ];
        }

        $rfq_details = RFQDetails::with('pr_details')->where('rfq_head_id',$rfq_head_id)->get()->unique('pr_details_id');
        foreach($rfq_details AS $d){
            $deliver_qty = PrReportDetails::where('pr_details_id',$d->pr_details_id)->value('delivered_qty');
            $remaining_qty = $d->pr_details->quantity - $deliver_qty;
            $pr_items_data[] = [
                'rfq_details_id'=>$d->id,
                'pr_details_id'=>$d->pr_details_id,
                'item_description'=>$d->pr_details->item_description,
                'uom'=>$d->pr_details->uom,
                'quantity'=>$d->pr_details->quantity,
                'remaining_qty'=>$remaining_qty,
                'rfq_vendor_id'=>$d->rfq_vendor_id,
            ];
        }

        return response()->json([
            'aoq_head_data'=>$head_data,
            'aoq_details_id'=>$aoq_details_id,
            'aoq_vendor_data'=>$vendor_data,
            'aoq_items_data'=>$items_data,
            // 'aoq_offers_data'=>$RFQOffers,
            'first_offers'=>$first_offers,
            'second_offers'=>$second_offers,
            'third_offers'=>$third_offers,
            'all_terms'=>$all_terms,
            'vendor_terms'=>$vendor_terms,
            'vendor_list'=>$rfq_vendors,
            'pr_items_data'=>$pr_items_data,
            'letters'=>$letters,
            'currency'=>$currency,
            'count_rfq_vendors'=>$count_rfq_vendors,
        ],200);
    }

    public function vendor_offers($rfq_vendor_id,$rfq_head_id){
        $additional_vendor_items = RFQDetails::with('pr_details')->where('rfq_head_id',$rfq_head_id)->where('rfq_vendor_id',$rfq_vendor_id)->get();
        foreach($additional_vendor_items AS $vai){
            $deliver_qty = PrReportDetails::where('pr_details_id',$vai->pr_details_id)->value('delivered_qty');
            $remaining_qty = $vai->pr_details->quantity - $deliver_qty;
            $vendor_aoq_items[] = [
                'rfq_details_id'=>$vai->id,
                'pr_details_id'=>$vai->pr_details_id,
                'item_description'=>$vai->pr_details->item_description,
                'uom'=>$vai->pr_details->uom,
                'quantity'=>$vai->pr_details->quantity,
                'remaining_qty'=>$remaining_qty,
                'rfq_vendor_id'=>$vai->rfq_vendor_id,
            ];
        }

        $item_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('rfq_vendor_id',$rfq_vendor_id)->get();
        foreach($item_offers AS $io){
            $itemoffers[] = [
                'rfq_offer_id'=>$io->id,
                'rfq_details_id'=>$io->rfq_details_id,
                'offer'=>$io->offer,
                'unit_price'=>$io->unit_price,
                'offer_currency'=>$io->currency ?? 'PHP',
            ];
        }
        
        // return response()->json($itemoffers);
        return response()->json([
            'vendor_aoq_items'=>$vendor_aoq_items,
            'itemoffers'=>$itemoffers,
        ],200);
    }

    public function add_aoq_vendor(Request $request){
        $aoq_v['aoq_head_id']=$request->input('aoq_head_id');
        $aoq_v['rfq_vendor_id']=$request->input('rfq_vendor_id');
        $aoq_v['created_at']=date('Y-m-d H:i:s');
        AOQDetails::create($aoq_v);

        $aoq_items_offers = $request->input('itemoffers');
        foreach(json_decode($aoq_items_offers) as $aio){
            if(RFQOffers::where('id','=',$aio->rfq_offer_id)->exists()){
                $update_vendor_offer = RFQOffers::find($aio->rfq_offer_id);
                $update_vendor_offer->offer = $aio->offer;
                $update_vendor_offer->unit_price = $aio->unit_price;
                $update_vendor_offer->currency = $aio->offer_currency;
                $update_vendor_offer->save();
            }
        }
    }



    public function aoq_donete_details($aoq_head_id,$aoq_details_id, $start = 'a', $count = 26){
        $letters = [];
        $startAscii = ord($start);

        for ($i = 0; $i < $count; $i++) {
            $letters[] = chr($startAscii + $i);
        }

        $userid = Auth::id();
        $rfq_head_id = AOQHead::where('id',$aoq_head_id)->value('rfq_head_id');
        $rfq_vendor_id = AOQDetails::where('id',$aoq_details_id)->value('rfq_vendor_id');
        $aoq_head = AOQHead::where('id',$aoq_head_id)->get();
        $max_id = AOQDetails::where('aoq_head_id',$aoq_head_id)->max('id');
        $previous = AOQDetails::where('aoq_head_id',$aoq_head_id)->where('id', '<', $aoq_details_id)->orderBy('id', 'desc')->first();
        $next = AOQDetails::where('aoq_head_id',$aoq_head_id)->where('id', '>', $aoq_details_id)->orderBy('id')->first();
        $awarded_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->where('awarded',1)->get();
        $count_awarded =$awarded_offers->count();
        foreach($aoq_head AS $ah){
            $head_data = [
                'aoq_head_id'=>$ah->id,
                'aoq_no'=>$ah->aoq_no,
                'status'=>$ah->status,
                'aoq_status'=>$ah->aoq_status,
                'aoq_date'=>date('F j, Y', strtotime($ah->aoq_date)),
                'rfq_no'=>$ah->rfq_no,
                'rfq_head_id'=>$ah->rfq_head_id,
                'pr_no'=>$ah->pr_no,
                'department'=>PRHead::where('pr_no',$ah->pr_no)->value('department_name'),
                'enduse'=>PRHead::where('pr_no',$ah->pr_no)->value('enduse'),
                'purpose'=>PRHead::where('pr_no',$ah->pr_no)->value('purpose'),
                'requestor'=>PRHead::where('pr_no',$ah->pr_no)->value('requestor'),
                'date_needed'=>date('F j, Y', strtotime($ah->date_needed)),
                'prepared_by_name'=>User::where('id',$ah->prepared_by)->value('name'),
            ];
        }

        // $aoq_details = AOQDetails::with('rfq_vendor')->where('id',$aoq_details_id)->get();
        $vendor_details =RFQVendor::with('vendor_details')->where('id',$rfq_vendor_id)->get();
        foreach($vendor_details AS $vd){
            $vendor_data = [
                'rfq_vendor_id'=>$vd->id,
                'vendor_name'=>$vd->vendor_name,
                'vendor_identifier'=>$vd->vendor_identifier,
                'contact_person'=>$vd->vendor_details->contact_person,
                'phone'=>$vd->vendor_details->phone,
                'count_awarded'=>$count_awarded,
            ];

            $vendor_terms = RFQVendorTerms::where('rfq_vendor_id',$vd->id)->get();
            foreach($vendor_terms AS $vt){
                $vendor_terms[] = [
                    'terms_id'=>$vt->id,
                    'rfq_vendor_id'=>$vt->rfq_vendor_id,
                    'terms'=>$vt->terms,
                ];
            }
        }


            $aoq_items = RFQDetails::with('pr_details')->where('rfq_head_id',$rfq_head_id)->where('rfq_vendor_id',$rfq_vendor_id)->get()->unique('pr_details_id');
            foreach($aoq_items AS $ai){
                $items_data[] = [
                    'rfq_details_id'=>$ai->id,
                    'pr_details_id'=>$ai->pr_details_id,
                    'item_description'=>$ai->pr_details->item_description,
                    'uom'=>$ai->pr_details->uom,
                    'quantity'=>$ai->pr_details->quantity,
                    'rfq_vendor_id'=>$ai->rfq_vendor_id,
                ];
            }

            $rfq_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('rfq_vendor_id',$rfq_vendor_id)->get();
            foreach($rfq_offers AS $o){
                $min_price = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('unit_price','!=',0)->where('pr_details_id',$o->pr_details_id)->whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->min('unit_price');
                $RFQOffers[] = [
                    'min_price'=>$min_price,
                    'rfq_offer_id'=>$o->id,
                    'rfq_details_id'=>$o->rfq_details_id,
                    'rfq_vendor_id'=>$o->rfq_vendor_id,
                    'offer'=>$o->offer,
                    'unit_price'=>$o->unit_price,
                    'offer_currency'=>$o->currency,
                    'awarded'=>$o->awarded,
                    'comments'=>$o->remarks,
                ];
            }

        return response()->json([
            'aoq_head_data'=>$head_data,
            'aoq_vendor_data'=>$vendor_data,
            'aoq_items_data'=>$items_data,
            'aoq_offers_data'=>$RFQOffers,
            'letters'=>$letters,
            'vendor_terms'=>$vendor_terms,
            'max_id'=>$max_id,
            'previous'=>$previous,
            'next'=>$next,
            'count_awarded'=>$count_awarded,
        ],200);
    }

    public function update_offers_awarded(Request $request){
        $rfq_head_id = $request->input('rfq_head_id');
        $rfq_offer_id = $request->input('rfq_offer_id');
        $pr_details_id = $request->input('pr_details_id');
        $update_o=RFQOffers::where('id',$rfq_offer_id)->update(['awarded'=>$request->input('awarded'),]);
        $update_awarded=RFQOffers::where('id','!=',$rfq_offer_id)->where('rfq_head_id',$rfq_head_id)->where('pr_details_id',$pr_details_id)->update(['awarded'=>0]);
    }

    public function update_offers_comments(Request $request){
        $rfq_offer_id = $request->input('rfq_offer_id');
        $update_o=RFQOffers::where('id',$rfq_offer_id)->update(['remarks'=>$request->input('comments')]);

    }

    public function save_aoq($aoq_head_id){
        $userid = Auth::id();
        $update_aoq_head=AOQHead::where('id',$aoq_head_id)->update([
            'awarded_by'=>$userid,
            'awarded_date'=>date('Y-m-d H:i:s'),
            'aoq_status'=>'Awarded',
            'status'=>'Saved'
        ]);

        $update_rfq_vendor=RFQVendor::whereIn('id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->update([
            'award_status'=>'Saved',
        ]);

        

        $pr_no= AOQHead::where('id',$aoq_head_id)->value('pr_no');
        $update_pr_status = PrReportDetails::whereIn('pr_details_id',RFQDetails::where('pr_no',$pr_no)->pluck('pr_details_id'))->update(['status' => 'Awarded']);

    }

    public function done_te_aoq($aoq_head_id){
        $update_aoq_status=AOQHead::where('id',$aoq_head_id)->update(['aoq_status'=>'Done TE']);

        $pr_no= AOQHead::where('id',$aoq_head_id)->value('pr_no');
        $update_pr_status = PrReportDetails::whereIn('pr_details_id',RFQDetails::where('pr_no',$pr_no)->pluck('pr_details_id'))->update(['status' => 'Done TE']);
    }

    public function update_aoq_draft($aoq_head_id){
        $update_draft=AOQHead::where('id',$aoq_head_id)->update(['status'=>'Draft']);

        $update_rfq_vendor=RFQVendor::whereIn('id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->update([
            'award_status'=>'Draft',
        ]);

    }

    public function cancel_aoq($aoq_head_id){
        $userid = Auth::id();
        $update_status = AOQHead::where('id','=', $aoq_head_id)->update([
            'status' => 'Cancelled',
            'cancelled_by' => $userid,
            'cancelled_date' => date('Y-m-d H:i:s'),
        ]);
    }

    public function export_aoq($aoq_head_id){
        return Excel::download(new AOQExport($aoq_head_id), 'Abstract of Quotation.xlsx');
    }
}
