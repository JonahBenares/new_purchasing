<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JOAOQSeries;
use App\Models\JOAOQHead;
use App\Models\JOAOQDetails;
use App\Models\JORFQHead;
use App\Models\JORFQLaborDetails;
use App\Models\JORFQMaterialDetails;
use App\Models\JORFQLaborOffers;
use App\Models\JORFQMaterialOffers;
use App\Models\JORFQVendor;
use App\Models\JORFQTerms;
use App\Models\JORHead;
use App\Models\JORLaborDetails;
use App\Models\JORMaterialDetails;
use App\Models\VendorDetails;
use App\Exports\JOAOQExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Config;

class JOAOQController extends Controller
{
    public function get_all_jo_aoq(Request $request){
        $all_aoq = JOAOQHead::orderby('aoq_date', 'DESC')->get();
        $x=0;
        $aoqarray=array();
        foreach($all_aoq AS $aa){
            $aoq_details_id = JOAOQDetails::where('jo_aoq_head_id',$aa->id)->orderBy('id', 'ASC')->value('id');
            $jor_head_id  = JORFQHead::where('id',$aa->jo_rfq_head_id)->value('jor_head_id');
            $department_name= JORHead::where('id',$jor_head_id)->value('department_name');
            // $enduse= JORHead::where('id',$jor_head_id)->value('enduse');
            $purpose= JORHead::where('id',$jor_head_id)->value('purpose');
            $requestor= JORHead::where('id',$jor_head_id)->value('requestor');
            $allvendors = JORFQVendor::with('vendor_details')->whereIn('id',JOAOQDetails::where('jo_aoq_head_id',$aa->id)->pluck('jo_rfq_vendor_id'))->orderby('vendor_name', 'ASC')->get();
            $all_vendors=[];
            foreach($allvendors AS $av){
                $labor_awarded_vendor = JORFQLaborOffers::where('jo_rfq_vendor_id',$av->id)->where('awarded',1)->get();
                $count_labor_awarded_vendor=$labor_awarded_vendor->count();
                $material_awarded_vendor = JORFQMaterialOffers::where('jo_rfq_vendor_id',$av->id)->where('awarded',1)->get();
                $count_material_awarded_vendor=$material_awarded_vendor->count();
                $all_vendors[] = [
                    'vendor_name'=>$av->vendor_name,
                    'identifier'=>$av->vendor_identifier,
                    'count_labor_awarded'=>$count_labor_awarded_vendor,
                    'count_material_awarded'=>$count_material_awarded_vendor,
                ];
            }

            $aoqarray[]=[
                'id'=>$aa->id,
                'status'=>$aa->status,
                'aoq_status'=>$aa->aoq_status,
                'aoq_details_id'=>$aoq_details_id,
                'vendor'=>$all_vendors,
                date('F j, Y', strtotime($aa->aoq_date)),
                $aa->jor_no,
                '',
                $department_name,
                // $enduse,
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

    public function all_jo_rfq_jor(){
        $jorlist=JORFQVendor::where('canvassed',1)->orderBy('jor_no','ASC')->get()->unique('jor_no');
        foreach($jorlist AS $jor){
                $jor_list[] = [
                    'jo_rfq_head_id'=>$jor->jo_rfq_head_id,
                    'jor_no'=>$jor->jor_no,
                ];
        }
        return response()->json($jor_list);
    }

    
    public function all_jo_rfq($jor_no){
        $jorfqlist=JORFQVendor::with('jo_rfq_head')->where('jor_no',$jor_no)->where('canvassed',1)->orderBy('jor_no','ASC')->get()->unique('jo_rfq_head_id');
        $jo_rfq_list = [];
        foreach($jorfqlist AS $jrfq){
            $jo_rfq_aoq = JOAOQHead::where('jo_rfq_head_id',$jrfq->jo_rfq_head_id)->get();
            $count_saved_jo_rfq=$jo_rfq_aoq->count();
                $jo_rfq_list[] = [
                    'rfq_no'=>$jrfq->jo_rfq_head->rfq_no,
                    'jo_rfq_head_id'=>$jrfq->jo_rfq_head_id,
                    'count_aoq'=>$count_saved_jo_rfq,
                ];
        }
        return response()->json($jo_rfq_list);
    }

    public function create_new_jo_aoq($jo_rfq_head_id){
        $userid = Auth::id();
        $curr_year = date('Y');
        $company=Config::get('constants.company');
        if(JOAOQSeries::where('year', '=', $curr_year)->exists()) {
            $aoq = JOAOQSeries::where('year', '=', $curr_year)->max('series') + 1;
            $max_value = str_pad($aoq,4,"0",STR_PAD_LEFT);;
        } else {
            $max_value = '0001';
        }
        $aoq_no = 'JO-AOQ-'.$curr_year.'-'.$max_value."-".$company;

        $jo_aoq_head = JORFQHead::with('jor_head')->where('id',$jo_rfq_head_id)->get();
        foreach($jo_aoq_head AS $ah){
            $head_data = [
                'aoq_date'=>date("Y-m-d"),
                'rfq_no'=>$ah->rfq_no,
                'jo_rfq_head_id'=>$ah->id,
                'jor_no'=>$ah->jor_no,
                'department'=>$ah->jor_head->department_name,
                // 'enduse'=>$ah->jor_head->enduse,
                'purpose'=>$ah->jor_head->purpose,
                'requestor'=>$ah->jor_head->requestor,
                'requestor_id'=>$ah->jor_head->requestor_id,
                'prepared_by'=>$userid,
                'prepared_by_name'=>User::where('id',$userid)->value('name'),
            ];
        }

        $rfqvendor = JORFQVendor::where('jo_rfq_head_id',$jo_rfq_head_id)->where('canvassed',1)->orderBy('vendor_name','ASC')->get();
        $rfq_vendor = [];
        foreach($rfqvendor AS $rv){
            $rfq_vendor[] = [
                'vendor_checkbox'=>0,
                'jo_rfq_vendor_id'=>$rv->id,
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

    public function add_jo_aoq_head(Request $request){
        $aoqhead['aoq_no']=$request->input('aoq_no');
        $aoqhead['jo_rfq_head_id']=$request->input('jo_rfq_head_id');
        $aoqhead['jor_no']=$request->input('jor_no');
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
        $jo_aoq_head_id=JOAOQHead::insertGetId($aoqhead);

        $aoq_no = $request->input('aoq_no');
        $ser = explode("-",$aoq_no);
        $series = $ser[3];

        JOAOQSeries::create([
            'year' => date("Y"),
            'series'=>$series
        ]);

        $aoq_vendors = $request->input('aoq_vendors');
        foreach(json_decode($aoq_vendors) as $av){
            if($av->vendor_checkbox != 0){
                $aoq_v['jo_aoq_head_id']=$jo_aoq_head_id;
                $aoq_v['jo_rfq_vendor_id']=$av->jo_rfq_vendor_id;
                $aoq_v['created_at']=date('Y-m-d H:i:s');
                JOAOQDetails::create($aoq_v);
            }


            // $update_pr_status = PrReportDetails::whereIn('pr_details_id',RFQDetails::where('rfq_vendor_id',$av->rfq_vendor_id)->pluck('pr_details_id'))->update(['status' => 'For TE']);
        }

        return $jo_aoq_head_id;
    }

    public function jo_aoq_head_details($jo_aoq_head_id, $start = 'a', $count = 26){
        $letters = [];
        $startAscii = ord($start);

        for ($i = 0; $i < $count; $i++) {
            $letters[] = chr($startAscii + $i);
        }
        $userid = Auth::id();
        $currency=Config::get('constants.currency');
        $jo_rfq_head_id = JOAOQHead::where('id',$jo_aoq_head_id)->value('jo_rfq_head_id');
        $all_terms =JORFQTerms::whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->orderBy('id','ASC')->get();
        $jo_aoq_details_id = JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->orderBy('id', 'ASC')->value('id');
        $aoq_head = JOAOQHead::where('id',$jo_aoq_head_id)->get();
        foreach($aoq_head AS $ah){
            $head_data = [
                'jo_aoq_head_id'=>$ah->id,
                'aoq_no'=>$ah->aoq_no,
                'status'=>$ah->status,
                'aoq_status'=>$ah->aoq_status,
                'aoq_date'=>date('F j, Y', strtotime($ah->aoq_date)),
                'rfq_no'=>$ah->rfq_no,
                'jo_rfq_head_id'=>$jo_rfq_head_id,
                'jor_no'=>$ah->jor_no,
                'cancelled_date'=>date('F j, Y', strtotime($ah->cancelled_date)),
                'cancelled_name'=>User::where('id',$ah->cancelled_by)->value('name'),
                'general_description'=>JORHead::where('jor_no',$ah->jor_no)->value('general_description'),
                'project_activity'=>JORHead::where('jor_no',$ah->jor_no)->value('project_activity'),
                'department'=>JORHead::where('jor_no',$ah->jor_no)->value('department_name'),
                // 'enduse'=>JORHead::where('jor_no',$ah->jor_no)->value('enduse'),
                'purpose'=>JORHead::where('jor_no',$ah->jor_no)->value('purpose'),
                'requestor'=>JORHead::where('jor_no',$ah->jor_no)->value('requestor'),
                'date_needed'=>date('F j, Y', strtotime($ah->date_needed)),
                'prepared_by_name'=>User::where('id',$ah->prepared_by)->value('name'),
                'received_by_name'=>User::where('id',$ah->received_by)->value('name'),
                'award_recommended_by_name'=>User::where('id',$ah->award_recommended_by)->value('name'),
                'recommended_by_name'=>User::where('id',$ah->recommended_by)->value('name'),
                'approved_by_name'=>User::where('id',$ah->approved_by)->value('name'),
            ];
        }

        $rfq_vendors = JORFQVendor::where('jo_rfq_head_id',$jo_rfq_head_id)->whereNotIn('id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('canvassed',1)->orderby('vendor_name', 'ASC')->get();
        $count_rfq_vendors =$rfq_vendors->count();

        $canvassed_aoq_vendor = JORFQVendor::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('canvassed',1)->get();
        $count_canvassed_aoq_v=$canvassed_aoq_vendor->count();

        $aoq_details = JOAOQDetails::with('jo_rfq_vendor')->where('jo_aoq_head_id',$jo_aoq_head_id)->get();
        $count_aoq_vendors =$aoq_details->count();
        foreach($aoq_details AS $ad){
            $vendor_data[] = [
                'jo_rfq_vendor_id'=>$ad->jo_rfq_vendor->id,
                'vendor_name'=>$ad->jo_rfq_vendor->vendor_name,
                'vendor_identifier'=>$ad->jo_rfq_vendor->vendor_identifier,
                'vendor_details_id'=>$ad->jo_rfq_vendor->vendor_details_id,
                'contact_person'=>VendorDetails::where('id',$ad->jo_rfq_vendor->vendor_details_id)->value('contact_person'),
                'phone'=>VendorDetails::where('id',$ad->jo_rfq_vendor->vendor_details_id)->value('phone'),
            ];

            
            $vendor_terms = JORFQTerms::where('jo_rfq_vendor_id',$ad->jo_rfq_vendor_id)->get();
            foreach($vendor_terms AS $vt){
                $vendor_terms[] = [
                    'terms_id'=>$vt->id,
                    'jo_rfq_vendor_id'=>$vt->jo_rfq_vendor_id,
                    'terms'=>$vt->terms,
                ];
            }
        }

        $aoq_labor_items = JORFQLaborDetails::with('jor_labor_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->get()->unique('jor_labor_details_id');
        // $first_labor_offers = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('offer_no',1)->get();
        // $second_labor_offers = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('offer_no',2)->get();
        // $third_labor_offers = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('offer_no',3)->get();
        $first_labor_offers = JORFQLaborOffers::join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_labor_offer.jo_rfq_vendor_id')
        ->where('jo_rfq_labor_offer.jo_rfq_head_id',$jo_rfq_head_id)
        ->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))
        ->where('jo_rfq_labor_offer.offer_no',1)
        ->select('jo_rfq_labor_offer.*') // Select necessary columns
        ->orderBy('jo_rfq_vendor.vendor_name', 'asc') // Order by column in related table
        ->get();
        $second_labor_offers = JORFQLaborOffers::join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_labor_offer.jo_rfq_vendor_id')
        ->where('jo_rfq_labor_offer.jo_rfq_head_id',$jo_rfq_head_id)
        ->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))
        ->where('jo_rfq_labor_offer.offer_no',2)
        ->select('jo_rfq_labor_offer.*') // Select necessary columns
        ->orderBy('jo_rfq_vendor.vendor_name', 'asc') // Order by column in related table
        ->get();
        $third_labor_offers = JORFQLaborOffers::join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_labor_offer.jo_rfq_vendor_id')
        ->where('jo_rfq_labor_offer.jo_rfq_head_id',$jo_rfq_head_id)
        ->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))
        ->where('jo_rfq_labor_offer.offer_no',3)
        ->select('jo_rfq_labor_offer.*') // Select necessary columns
        ->orderBy('jo_rfq_vendor.vendor_name', 'asc') // Order by column in related table
        ->get();
        foreach($aoq_labor_items AS $al){
            $min_price = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('unit_price','!=',0)->where('jor_labor_details_id',$al->jor_labor_details_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->min('unit_price');
            $labor_data[] = [
                'jo_rfq_details_id'=>$al->id,
                'jor_labor_details_id'=>$al->jor_labor_details_id,
                'scope_of_work'=>$al->jor_labor_details->scope_of_work,
                'uom'=>$al->jor_labor_details->uom,
                'quantity'=>$al->jor_labor_details->quantity,
                'jo_rfq_vendor_id'=>$al->jo_rfq_vendor_id,
                'min_price'=>$min_price,
                'awarded'=>$al->awarded,
            ];
        }

        $aoq_material_items = JORFQMaterialDetails::with('jor_material_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->get()->unique('jor_material_details_id');
        // $first_offers = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('offer_no',1)->get();
        // $second_offers = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('offer_no',2)->get();
        // $third_offers = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('offer_no',3)->get();
        $first_offers = JORFQMaterialOffers::join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_material_offer.jo_rfq_vendor_id')
        ->where('jo_rfq_material_offer.jo_rfq_head_id',$jo_rfq_head_id)
        ->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))
        ->where('jo_rfq_material_offer.offer_no',1)
        ->select('jo_rfq_material_offer.*') // Select necessary columns
        ->orderBy('jo_rfq_vendor.vendor_name', 'asc') // Order by column in related table
        ->get();
        $second_offers = JORFQMaterialOffers::join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_material_offer.jo_rfq_vendor_id')
        ->where('jo_rfq_material_offer.jo_rfq_head_id',$jo_rfq_head_id)
        ->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))
        ->where('jo_rfq_material_offer.offer_no',2)
        ->select('jo_rfq_material_offer.*') // Select necessary columns
        ->orderBy('jo_rfq_vendor.vendor_name', 'asc') // Order by column in related table
        ->get();
        $third_offers = JORFQMaterialOffers::join('jo_rfq_vendor', 'jo_rfq_vendor.id', '=', 'jo_rfq_material_offer.jo_rfq_vendor_id')
        ->where('jo_rfq_material_offer.jo_rfq_head_id',$jo_rfq_head_id)
        ->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))
        ->where('jo_rfq_material_offer.offer_no',3)
        ->select('jo_rfq_material_offer.*') // Select necessary columns
        ->orderBy('jo_rfq_vendor.vendor_name', 'asc') // Order by column in related table
        ->get();
        foreach($aoq_material_items AS $am){
            $min_price = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('unit_price','!=',0)->where('jor_material_details_id',$am->jor_material_details_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->min('unit_price');
            $material_data[] = [
                'jo_rfq_details_id'=>$am->id,
                'jor_material_details_id'=>$am->jor_material_details_id,
                'item_description'=>$am->jor_material_details->item_description,
                'uom'=>$am->jor_material_details->uom,
                'quantity'=>$am->jor_material_details->quantity,
                'jo_rfq_vendor_id'=>$am->jo_rfq_vendor_id,
                'min_price'=>$min_price,
                'awarded'=>$am->awarded,
            ];
        }

        return response()->json([
            'aoq_head_data'=>$head_data,
            'jo_aoq_details_id'=>$jo_aoq_details_id,
            'aoq_vendor_data'=>$vendor_data,
            // 'aoq_items_data'=>$items_data,
            'labor_data'=>$labor_data,
            'first_labor_offers'=>$first_labor_offers,
            'second_labor_offers'=>$second_labor_offers,
            'third_labor_offers'=>$third_labor_offers,
            'material_data'=>$material_data,
            'first_offers'=>$first_offers,
            'second_offers'=>$second_offers,
            'third_offers'=>$third_offers,
            'all_terms'=>$all_terms,
            'vendor_terms'=>$vendor_terms,
            'vendor_list'=>$rfq_vendors,
            'letters'=>$letters,
            'currency'=>$currency,
            'count_rfq_vendors'=>$count_rfq_vendors,
            'count_canvassed_aoq_v'=>$count_canvassed_aoq_v,
            'count_aoq_vendors'=>$count_aoq_vendors,
        ],200);
    }

    public function joaoq_donete_details($jo_aoq_head_id,$jo_aoq_details_id, $start = 'a', $count = 26){
        $letters = [];
        $startAscii = ord($start);

        for ($i = 0; $i < $count; $i++) {
            $letters[] = chr($startAscii + $i);
        }

        $userid = Auth::id();
        $jo_rfq_head_id = JOAOQHead::where('id',$jo_aoq_head_id)->value('jo_rfq_head_id');
        $jo_rfq_vendor_id = JOAOQDetails::where('id',$jo_aoq_details_id)->value('jo_rfq_vendor_id');
        $aoq_head = JOAOQHead::where('id',$jo_aoq_head_id)->get();
        $max_id = JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->max('id');
        $previous = JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->where('id', '<', $jo_aoq_details_id)->orderBy('id', 'desc')->first();
        $next = JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->where('id', '>', $jo_aoq_details_id)->orderBy('id')->first();

        $awarded_labor_offers = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('awarded',1)->get();
        $count_labor_awarded =$awarded_labor_offers->count();

        $awarded_material_offers = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('awarded',1)->get();
        $count_material_awarded =$awarded_material_offers->count();
        foreach($aoq_head AS $ah){
            $head_data = [
                'jo_aoq_head_id'=>$ah->id,
                'aoq_no'=>$ah->aoq_no,
                'status'=>$ah->status,
                'aoq_status'=>$ah->aoq_status,
                'aoq_date'=>date('F j, Y', strtotime($ah->aoq_date)),
                'rfq_no'=>$ah->rfq_no,
                'jo_rfq_head_id'=>$ah->jo_rfq_head_id,
                'jor_no'=>$ah->jor_no,
                'cancelled_date'=>date('F j, Y', strtotime($ah->cancelled_date)),
                'cancelled_name'=>User::where('id',$ah->cancelled_by)->value('name'),
                'department'=>JORHead::where('jor_no',$ah->jor_no)->value('department_name'),
                'general_description'=>JORHead::where('jor_no',$ah->jor_no)->value('general_description'),
                'project_activity'=>JORHead::where('jor_no',$ah->jor_no)->value('project_activity'),
                'purpose'=>JORHead::where('jor_no',$ah->jor_no)->value('purpose'),
                'requestor'=>JORHead::where('jor_no',$ah->jor_no)->value('requestor'),
                'date_needed'=>date('F j, Y', strtotime($ah->date_needed)),
                'prepared_by_name'=>User::where('id',$ah->prepared_by)->value('name'),
            ];
        }

        $vendor_details = JORFQVendor::with('vendor_details')->where('id',$jo_rfq_vendor_id)->get();
        foreach($vendor_details AS $vd){
            $vendor_data = [
                'jo_rfq_vendor_id'=>$vd->id,
                'vendor_name'=>$vd->vendor_name,
                'vendor_identifier'=>$vd->vendor_identifier,
                'contact_person'=>$vd->vendor_details->contact_person,
                'phone'=>$vd->vendor_details->phone,
                'count_labor_awarded'=>$count_labor_awarded,
                'count_material_awarded'=>$count_material_awarded,
            ];

            $vendor_terms = JORFQTerms::where('jo_rfq_vendor_id',$vd->id)->get();
            foreach($vendor_terms AS $vt){
                $vendor_terms[] = [
                    'terms_id'=>$vt->id,
                    'jo_rfq_vendor_id'=>$vt->jo_rfq_vendor_id,
                    'terms'=>$vt->terms,
                ];
            }
        }


        $aoq_labor = JORFQLaborDetails::with('jor_labor_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->where('jo_rfq_vendor_id',$jo_rfq_vendor_id)->get()->unique('jor_labor_details_id');
            foreach($aoq_labor AS $al){
                $labor_data[] = [
                    'jo_rfq_labor_details_id'=>$al->id,
                    'jor_labor_details_id'=>$al->jor_labor_details_id,
                    'scope_of_work'=>$al->jor_labor_details->scope_of_work,
                    'uom'=>$al->jor_labor_details->uom,
                    'quantity'=>$al->jor_labor_details->quantity,
                    'jo_rfq_vendor_id'=>$al->jo_rfq_vendor_id,
                ];
            }

            $rfq_labor_offers = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('jo_rfq_vendor_id',$jo_rfq_vendor_id)->get();
            foreach($rfq_labor_offers AS $rlo){
                $min_price = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('unit_price','!=',0)->where('jor_labor_details_id',$rlo->jor_labor_details_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->min('unit_price');
                $RFQLaborOffers[] = [
                    'min_price'=>$min_price,
                    'jo_rfq_labor_offer_id'=>$rlo->id,
                    'jo_rfq_labor_details_id'=>$rlo->jo_rfq_labor_details_id,
                    'jo_rfq_vendor_id'=>$rlo->jo_rfq_vendor_id,
                    'offer'=>$rlo->offer,
                    'unit_price'=>$rlo->unit_price,
                    'labor_currency'=>$rlo->currency,
                    'awarded'=>$rlo->awarded,
                    'comments'=>$rlo->remarks,
                ];
            }
        
        $aoq_material = JORFQMaterialDetails::with('jor_material_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->where('jo_rfq_vendor_id',$jo_rfq_vendor_id)->get()->unique('jor_material_details_id');
            foreach($aoq_material AS $am){
                $material_data[] = [
                    'jo_rfq_material_details_id'=>$am->id,
                    'jor_material_details_id'=>$am->jor_material_details_id,
                    'item_description'=>$am->jor_material_details->item_description,
                    'uom'=>$am->jor_material_details->uom,
                    'quantity'=>$am->jor_material_details->quantity,
                    'jo_rfq_vendor_id'=>$am->jo_rfq_vendor_id,
                ];
            }

        $rfq_material_offers = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('jo_rfq_vendor_id',$jo_rfq_vendor_id)->get();
            foreach($rfq_material_offers AS $rmo){
                $min_price = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('unit_price','!=',0)->where('jor_material_details_id',$rmo->jor_material_details_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->min('unit_price');
                $RFQMaterialOffers[] = [
                    'min_price'=>$min_price,
                    'jo_rfq_material_offer_id'=>$rmo->id,
                    'jo_rfq_material_details_id'=>$rmo->jo_rfq_material_details_id,
                    'jo_rfq_vendor_id'=>$rmo->jo_rfq_vendor_id,
                    'offer'=>$rmo->offer,
                    'unit_price'=>$rmo->unit_price,
                    'material_currency'=>$rmo->currency,
                    'awarded'=>$rmo->awarded,
                    'comments'=>$rmo->remarks,
                ];
            }

        $canvassed_aoq_vendor = JORFQVendor::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('canvassed',1)->get();
        $count_canvassed_aoq_v=$canvassed_aoq_vendor->count();

        $aoq_details = JOAOQDetails::with('jo_rfq_vendor')->where('jo_aoq_head_id',$jo_aoq_head_id)->get();
        $count_aoq_vendors =$aoq_details->count();

        return response()->json([
            'aoq_head_data'=>$head_data,
            'aoq_vendor_data'=>$vendor_data,
            'aoq_labor_data'=>$labor_data,
            'aoq_labor_offers_data'=>$RFQLaborOffers,
            'aoq_material_data'=>$material_data,
            'aoq_material_offers_data'=>$RFQMaterialOffers,
            'letters'=>$letters,
            'vendor_terms'=>$vendor_terms,
            'max_id'=>$max_id,
            'previous'=>$previous,
            'next'=>$next,
            'count_labor_awarded'=>$count_labor_awarded,
            'count_material_awarded'=>$count_material_awarded,
            'count_canvassed_aoq_v'=>$count_canvassed_aoq_v,
            'count_aoq_vendors'=>$count_aoq_vendors,
        ],200);
    }

    public function jo_vendor_offers($jo_rfq_vendor_id,$jo_rfq_head_id){
        $additional_vendor_labor = JORFQLaborDetails::with('jor_labor_details')->where('jo_rfq_vendor_id',$jo_rfq_vendor_id)->where('jo_rfq_vendor_id',$jo_rfq_vendor_id)->get();
        foreach($additional_vendor_labor AS $val){
            // $deliver_qty = PrReportDetails::where('pr_details_id',$vai->pr_details_id)->value('delivered_qty');
            // $remaining_qty = $vai->pr_details->quantity - $deliver_qty;
            $vendor_aoq_labor[] = [
                'jo_rfq_labor_details_id'=>$val->id,
                'jor_labor_details_id'=>$val->jor_labor_details_id,
                'scope_of_work'=>$val->jor_labor_details->scope_of_work,
                'uom'=>$val->jor_labor_details->uom,
                'quantity'=>$val->jor_labor_details->quantity,
                // 'remaining_qty'=>$remaining_qty,
                'jo_rfq_vendor_id'=>$val->jo_rfq_vendor_id,
            ];
        }

        $labor_offers = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('jo_rfq_vendor_id',$jo_rfq_vendor_id)->get();
        foreach($labor_offers AS $lo){
            $laboroffers[] = [
                'jo_rfq_labor_offer_id'=>$lo->id,
                'jo_rfq_labor_details_id'=>$lo->jo_rfq_labor_details_id,
                'offer'=>$lo->offer,
                'unit_price'=>$lo->unit_price,
                'labor_currency'=>$lo->currency ?? 'PHP',
            ];
        }

        $additional_vendor_material = JORFQMaterialDetails::with('jor_material_details')->where('jo_rfq_vendor_id',$jo_rfq_vendor_id)->where('jo_rfq_vendor_id',$jo_rfq_vendor_id)->get();
        foreach($additional_vendor_material AS $vam){
            // $deliver_qty = PrReportDetails::where('pr_details_id',$vai->pr_details_id)->value('delivered_qty');
            // $remaining_qty = $vai->pr_details->quantity - $deliver_qty;
            $vendor_aoq_material[] = [
                'jo_rfq_material_details_id'=>$vam->id,
                'jor_material_details_id'=>$vam->jor_material_details_id,
                'item_description'=>$vam->jor_material_details->item_description,
                'uom'=>$vam->jor_material_details->uom,
                'quantity'=>$vam->jor_material_details->quantity,
                // 'remaining_qty'=>$remaining_qty,
                'jo_rfq_vendor_id'=>$vam->jo_rfq_vendor_id,
            ];
        }

        $material_offers = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('jo_rfq_vendor_id',$jo_rfq_vendor_id)->get();
        foreach($material_offers AS $mo){
            $materialoffers[] = [
                'jo_rfq_material_offer_id'=>$mo->id,
                'jo_rfq_material_details_id'=>$mo->jo_rfq_material_details_id,
                'offer'=>$mo->offer,
                'unit_price'=>$mo->unit_price,
                'material_currency'=>$mo->currency ?? 'PHP',
            ];
        }
        
        return response()->json([
            'vendor_aoq_labor'=>$vendor_aoq_labor,
            'laboroffers'=>$laboroffers,
            'vendor_aoq_material'=>$vendor_aoq_material,
            'materialoffers'=>$materialoffers,
        ],200);
    }

    public function add_jo_aoq_vendor(Request $request){
        $aoq_v['jo_aoq_head_id']=$request->input('jo_aoq_head_id');
        $aoq_v['jo_rfq_vendor_id']=$request->input('jo_rfq_vendor_id');
        $aoq_v['created_at']=date('Y-m-d H:i:s');
        JOAOQDetails::create($aoq_v);

        $aoq_labor_offers = $request->input('laboroffers');
        foreach(json_decode($aoq_labor_offers) as $alo){
            if(JORFQLaborOffers::where('id','=',$alo->jo_rfq_labor_offer_id)->exists()){
                $update_vendor_labor_offer = JORFQLaborOffers::find($alo->jo_rfq_labor_offer_id);
                $update_vendor_labor_offer->offer = $alo->offer;
                $update_vendor_labor_offer->unit_price = $alo->unit_price;
                $update_vendor_labor_offer->currency = $alo->labor_currency;
                $update_vendor_labor_offer->save();
            }
        }

        $aoq_material_offers = $request->input('materialoffers');
        foreach(json_decode($aoq_material_offers) as $amo){
            if(JORFQMaterialOffers::where('id','=',$amo->jo_rfq_material_offer_id)->exists()){
                $update_vendor_labor_offer = JORFQMaterialOffers::find($amo->jo_rfq_material_offer_id);
                $update_vendor_labor_offer->offer = $amo->offer;
                $update_vendor_labor_offer->unit_price = $amo->unit_price;
                $update_vendor_labor_offer->currency = $amo->material_currency;
                $update_vendor_labor_offer->save();
            }
        }
    }

    public function update_labor_offers_awarded(Request $request){
        $jo_rfq_head_id = $request->input('jo_rfq_head_id');
        $jo_rfq_labor_offer_id = $request->input('jo_rfq_labor_offer_id');
        $jor_labor_details_id = $request->input('jor_labor_details_id');
        $update_lo=JORFQLaborOffers::where('id',$jo_rfq_labor_offer_id)->update(['awarded'=>$request->input('awarded'),]);
        $update_labor_awarded=JORFQLaborOffers::where('id','!=',$jo_rfq_labor_offer_id)->where('jo_rfq_head_id',$jo_rfq_head_id)->where('jor_labor_details_id',$jor_labor_details_id)->update(['awarded'=>0]);
    }

    public function update_labor_offers_comments(Request $request){
        $jo_rfq_labor_offer_id = $request->input('jo_rfq_labor_offer_id');
        $update_lo=JORFQLaborOffers::where('id',$jo_rfq_labor_offer_id)->update(['remarks'=>$request->input('comments')]);

    }

    public function update_material_offers_awarded(Request $request){
        $jo_rfq_head_id = $request->input('jo_rfq_head_id');
        $jo_rfq_material_offer_id = $request->input('jo_rfq_material_offer_id');
        $jor_material_details_id = $request->input('jor_material_details_id');
        $update_lo=JORFQMaterialOffers::where('id',$jo_rfq_material_offer_id)->update(['awarded'=>$request->input('awarded'),]);
        $update_material_awarded=JORFQMaterialOffers::where('id','!=',$jo_rfq_material_offer_id)->where('jo_rfq_head_id',$jo_rfq_head_id)->where('jor_material_details_id',$jor_material_details_id)->update(['awarded'=>0]);
    }

    public function update_material_offers_comments(Request $request){
        $jo_rfq_material_offer_id = $request->input('jo_rfq_material_offer_id');
        $update_lo=JORFQMaterialOffers::where('id',$jo_rfq_material_offer_id)->update(['remarks'=>$request->input('comments')]);

    }

    public function done_te_jo_aoq($jo_aoq_head_id){
        $update_joaoq_status=JOAOQHead::where('id',$jo_aoq_head_id)->update(['aoq_status'=>'Done TE']);

        // $jor_no= JOAOQHead::where('id',$jo_aoq_head_id)->value('jor_no');
        // $update_pr_status = PrReportDetails::whereIn('pr_details_id',RFQDetails::where('pr_no',$pr_no)->pluck('pr_details_id'))->update(['status' => 'Done TE']);
    }

    public function update_jo_aoq_draft($jo_aoq_head_id){
        $update_draft=JOAOQHead::where('id',$jo_aoq_head_id)->update(['status'=>'Draft']);

        $update_rfq_vendor=JORFQVendor::whereIn('id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->update([
            'award_status'=>'Draft',
        ]);

    }

    public function open_jo_aoq($jo_aoq_head_id){
        $update_awarded_open_aoq=JOAOQHead::where('id',$jo_aoq_head_id)->update([
            'status'=>null,
            'aoq_status'=>'Done TE',
        ]);

        $update_canvass_open_aoq=JORFQVendor::whereIn('id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->update([
            'canvassed'=>0,
        ]);
    }

    public function save_jo_aoq($jo_aoq_head_id){
        $userid = Auth::id();
        $update_joaoq_head=JOAOQHead::where('id',$jo_aoq_head_id)->update([
            'awarded_by'=>$userid,
            'awarded_date'=>date('Y-m-d H:i:s'),
            'aoq_status'=>'Awarded',
            'status'=>'Saved'
        ]);

        $update_jo_rfq_vendor=JORFQVendor::whereIn('id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->update([
            'award_status'=>'Saved',
        ]);

        // $jor_no= JOAOQHead::where('id',$jo_aoq_head_id)->value('jor_no');
        // $update_pr_status = PrReportDetails::whereIn('pr_details_id',RFQDetails::where('pr_no',$pr_no)->pluck('pr_details_id'))->update(['status' => 'Awarded']);

    }

    public function cancel_jo_aoq($jo_aoq_head_id){
        $userid = Auth::id();
        $update_status = JOAOQHead::where('id','=', $jo_aoq_head_id)->update([
            'status' => 'Cancelled',
            'cancelled_by' => $userid,
            'cancelled_date' => date('Y-m-d H:i:s'),
        ]);
    }

    public function export_jo_aoq($jo_aoq_head_id){
        return Excel::download(new JOAOQExport($jo_aoq_head_id), 'JO Abstract of Quotation.xlsx');
    }

    public function joaoq_status($jo_aoq_head_id){
        $aoq_status = JOAOQHead::where('id',$jo_aoq_head_id)->value('aoq_status');
        $jo_rfq_head_id = JOAOQHead::where('id',$jo_aoq_head_id)->value('jo_rfq_head_id');
        $aoq_details_id = JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->orderBy('id', 'ASC')->value('id');
        $aoq_vendor = JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->get();
        $count_aoq_vendor=$aoq_vendor->count();
        $canvassed_aoq_vendor = JORFQVendor::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('canvassed',1)->get();
        $count_canvassed_aoq_v=$canvassed_aoq_vendor->count();


        return response()->json([
            'aoq_status'=>$aoq_status,
            'aoq_details_id'=>$aoq_details_id,
            'count_aoq_vendor'=>$count_aoq_vendor,
            'count_canvassed'=>$count_canvassed_aoq_v,
        ],200);
    }

}
