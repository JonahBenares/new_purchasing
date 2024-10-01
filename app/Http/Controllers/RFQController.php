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
use App\Models\VendorDetails;
use Illuminate\Support\Facades\Auth;

class RFQController extends Controller
{
    public function get_all_rfq(Request $request){
        $all_rfq = RFQHead::orderby('rfq_no', 'ASC')->get();
        $x=0;
        $rfqarray=array();
        foreach($all_rfq AS $ar){
            $all_vendors = RFQVendor::where('rfq_head_id',$ar->id)->orderby('vendor_name', 'ASC')->get();
            $rfqarray[]=[
                'id'=>$ar->id,
                $ar->pr_no,
                $ar->rfq_no,
                $ar->rfq_name,
                $ar->rfq_date,
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
        $pr_list=PRHead::where('status','Saved')->orderBy('pr_no','ASC')->get()->unique('pr_no');
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
        if(RFQSeries::where('year', '=', $curr_year)->exists()) {
            $rfq = RFQSeries::where('year', '=', $curr_year)->max('series') + 1;
            $max_value = str_pad($rfq,4,"0",STR_PAD_LEFT);;
        } else {
            $max_value = '0001';
        }
        $rfq_no = 'RFQ-'.$curr_year.'-'.$max_value;

        $head = PRHead::where('id',$pr_head_id)->where('status','Saved')->get();
        $userid = Auth::id();
        foreach($head AS $h){
            $PRHead = [
                'pr_head_id'=>$h->id,
                'date_prepared'=>$h->date_prepared,
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
                $PRDetails[] = [
                    'checkbox'=>0,
                    'pr_details_id'=>$d->id,
                    'date_needed'=>$d->date_needed,
                    'item_description'=>$d->item_description,
                    'quantity'=>$d->quantity,
                    'uom'=>$d->uom,
                    'pn_no'=>$d->pn_no,
                    'wh_stocks'=>$d->wh_stocks,
                ];
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
                $rfq_v['created_at']=date('Y-m-d H:i:s');
                $rfq_vendor_id=RFQVendor::insertGetId($rfq_v);

                foreach(json_decode($rfq_items) as $ri){
                    $rfq_i['rfq_head_id']=$rfq_head_id;
                    $rfq_i['rfq_vendor_id']=$rfq_vendor_id;
                    $rfq_i['pr_details_id']=$ri->pr_details_id;
                    $rfq_i['pr_no']=$request->input('pr_no');
                    $rfq_i['created_at']=date('Y-m-d H:i:s');
                    $rfq_details_id=RFQDetails::insertGetId($rfq_i);

                    for($x=0;$x<3;$x++){
                        RFQOffers::create([
                            'rfq_head_id'=>$rfq_head_id,
                            'rfq_vendor_id'=>$rfq_vendor_id,
                            'rfq_details_id'=>$rfq_details_id,
                            'pr_details_id'=>$ri->pr_details_id,
                            'pr_no'=>$request->input('pr_no'),
                            'uom'=>$ri->uom,
                        ]);
                    }
                }
            }
            
    
            return $rfq_head_id;

        }
}
