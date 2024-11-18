<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JORFQSeries;
use App\Models\JORFQHead;
use App\Models\JORFQLaborDetails;
use App\Models\JORFQMaterialDetails;
use App\Models\JORFQLaborOffers;
use App\Models\JORFQMaterialOffers;
use App\Models\JORFQVendor;
use App\Models\JORFQTerms;
use App\Models\JORHead;
use App\Models\JORNotes;
use App\Models\JORLaborDetails;
use App\Models\JORMaterialDetails;
// use App\Models\JORReportDetails;
use App\Models\VendorDetails;
use App\Models\VendorTerms;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Config;

class JORFQController extends Controller
{
    public function get_all_jo_rfq(Request $request){
        $all_jo_rfq = JORFQHead::with('jor_head')->orderby('rfq_no', 'DESC')->get();
        $x=0;
        $jorfqarray=array();
        foreach($all_jo_rfq AS $ar){
            $all_vendors = JORFQVendor::with('vendor_details')->where('jo_rfq_head_id',$ar->id)->orderby('vendor_name', 'ASC')->get();
            $jorfqarray[]=[
                'id'=>$ar->id,
                'vendor'=>$all_vendors,
                date('F j, Y', strtotime($ar->rfq_date)),
                $ar->jor_no,
                $ar->rfq_no,
                $ar->rfq_name,
                '',
                $ar->jor_head->project_activity,
                ''
            ];
            $x++;
        }
        return response()->json([
            'jorfqarray'=>$jorfqarray,
        ],200);
    }

    public function all_jor(){
        $jorlist=JORHead::where('status','Saved')->orderBy('jor_no','ASC')->get()->unique('jor_no');
        foreach($jorlist AS $jor){
            $jor_in_rfq = JORFQHead::where('jor_head_id',$jor->id)->get();
            $count_jor_in_rfq=$jor_in_rfq->count();
            $jor_list[] = [
                'id'=>$jor->id,
                'jor_no'=>$jor->jor_no,
                'count_jor_in_rfq' => $count_jor_in_rfq,
            ];
        }
        return response()->json($jor_list);
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

    public function get_jor_data($jor_head_id){
        $curr_year = date('Y');
        $curr_mo = date('m');
        $company=Config::get('constants.company');
        if(JORFQSeries::where('year', '=', $curr_year)->exists()) {
            $rfq = JORFQSeries::where('year', '=', $curr_year)->max('series') + 1;
            $max_value = str_pad($rfq,4,"0",STR_PAD_LEFT);;
        } else {
            $max_value = '0001';
        }
        $rfq_no = 'JO-RFQ-'.$curr_year.'-'.$max_value."-".$company;

        $head = JORHead::where('id',$jor_head_id)->where('status','Saved')->get();
        $userid = Auth::id();
        foreach($head AS $h){
            $JORHead = [
                'jor_head_id'=>$h->id,
                'date_prepared'=>date('F j, Y', strtotime($h->date_prepared)),
                'location'=>$h->location,
                'jor_no'=>$h->jor_no,
                'site_jor'=>$h->site_jor,
                'department_name'=>$h->department_name,
                'enduse'=>$h->enduse,
                'purpose'=>$h->purpose,
                'process_code'=>$h->process_code,
                'urgency'=>$h->urgency,
                'project_activity'=>$h->project_activity,
                'user_id'=>$userid,
            ];
        }

        $labor_details = JORLaborDetails::where('jor_head_id',$jor_head_id)->where('status','Saved')->orderBy('scope_of_work','ASC')->get();
            foreach($labor_details AS $ld){
                // $deliver_qty = JORReportDetails::where('jor_labor_details_id',$ld->id)->value('delivered_qty');
                // $remaining_qty = $ld->quantity - $deliver_qty;
                // if($remaining_qty != 0){
                    $LaborDetails[] = [
                        'labor_checkbox'=>0,
                        'jor_labor_details_id'=>$ld->id,
                        'date_needed'=>$ld->date_needed,
                        'scope_of_work'=>$ld->scope_of_work,
                        'quantity'=>$ld->quantity,
                        // 'remaining_qty'=>$remaining_qty,
                        'uom'=>$ld->uom,
                        'pn_no'=>$ld->pn_no,
                        'wh_stocks'=>$ld->wh_stocks,
                    ];
                // }
            }

        $material_details = JORMaterialDetails::where('jor_head_id',$jor_head_id)->where('status','Saved')->orderBy('item_description','ASC')->get();
            foreach($material_details AS $md){
                // $deliver_qty = JORReportDetails::where('jor_material_details_id',$md->id)->value('delivered_qty');
                // $remaining_qty = $md->quantity - $deliver_qty;
                // if($remaining_qty != 0){
                    $MaterialDetails[] = [
                        'material_checkbox'=>0,
                        'jor_material_details_id'=>$md->id,
                        'date_needed'=>$md->date_needed,
                        'item_description'=>$md->item_description,
                        'quantity'=>$md->quantity,
                        // 'remaining_qty'=>$remaining_qty,
                        'uom'=>$md->uom,
                        'pn_no'=>$md->pn_no,
                        'wh_stocks'=>$md->wh_stocks,
                    ];
                // }
            }
            return response()->json([
                'JORHead'=>$JORHead,
                'LaborDetails'=>$LaborDetails,
                'MaterialDetails'=>$MaterialDetails,
                'rfq_no'=>$rfq_no,
            ],200);
        }

        public function add_jo_rfq(Request $request){
            $rfqhead['jor_head_id']=$request->input('jor_head_id');
            $rfqhead['jor_no']=$request->input('jor_no');
            $rfqhead['rfq_name']=$request->input('rfq_name');
            $rfqhead['rfq_no']=$request->input('rfq_no');
            $rfqhead['rfq_date']=$request->input('rfq_date');
            $rfqhead['user_id']=$request->input('user_id');
            $rfqhead['created_at']=date('Y-m-d H:i:s');
            $jo_rfq_head_id=JORFQHead::insertGetId($rfqhead);

            $rfq_no = $request->input('rfq_no');
            $ser = explode("-",$rfq_no);
            $series = $ser[3];
    
            JORFQSeries::create([
                'year' => date("Y"),
                'series'=>$series
            ]);

            $rfq_labor = $request->input('rfq_labor');
            $rfq_materials = $request->input('rfq_materials');
            $jo_rfq_vendors = $request->input('rfq_vendors');
            foreach(json_decode($jo_rfq_vendors) as $rv){
                $rfq_v['jo_rfq_head_id']=$jo_rfq_head_id;
                $rfq_v['jor_no']=$request->input('jor_no');
                $rfq_v['vendor_details_id']=$rv->vendor_details_id;
                $rfq_v['vendor_name']=$rv->vendor_name;
                $rfq_v['vendor_identifier']=$rv->identifier;
                $rfq_v['created_at']=date('Y-m-d H:i:s');
                $jo_rfq_vendor_id=JORFQVendor::insertGetId($rfq_v);

                $vendorterms = VendorTerms::where('vendor_details_id',$rv->vendor_details_id)->get();
                    foreach($vendorterms as $vt){
                            $new_rfq_terms['jo_rfq_vendor_id']=$jo_rfq_vendor_id;
                            $new_rfq_terms['terms']=$vt->terms;
                            JORFQTerms::create($new_rfq_terms);
                    }

                foreach(json_decode($rfq_labor) as $rl){
                    $rfq_l['jo_rfq_head_id']=$jo_rfq_head_id;
                    $rfq_l['jo_rfq_vendor_id']=$jo_rfq_vendor_id;
                    $rfq_l['jor_labor_details_id']=$rl->jor_labor_details_id;
                    $rfq_l['jor_no']=$request->input('jor_no');
                    // $rfq_l['remaining_qty']=$rl->remaining_qty;
                    $rfq_l['created_at']=date('Y-m-d H:i:s');
                    $jo_rfq_labor_details_id=JORFQLaborDetails::insertGetId($rfq_l);

                    // $update_status = PrReportDetails::where('pr_details_id','=', $ri->pr_details_id)->update(['status' => 'For Canvass']);

                    for($l=0;$l<3;$l++){
                        JORFQLaborOffers::create([
                            'offer_no'=>$l+1,
                            'jo_rfq_head_id'=>$jo_rfq_head_id,
                            'jo_rfq_vendor_id'=>$jo_rfq_vendor_id,
                            'jo_rfq_labor_details_id'=>$jo_rfq_labor_details_id,
                            'jor_labor_details_id'=>$rl->jor_labor_details_id,
                            'jor_no'=>$request->input('jor_no'),
                            // 'remaining_qty'=>$rl->remaining_qty,
                            'uom'=>$rl->uom,
                        ]);
                    }
                }

                foreach(json_decode($rfq_materials) as $rm){
                    $rfq_m['jo_rfq_head_id']=$jo_rfq_head_id;
                    $rfq_m['jo_rfq_vendor_id']=$jo_rfq_vendor_id;
                    $rfq_m['jor_material_details_id']=$rm->jor_material_details_id;
                    $rfq_m['jor_no']=$request->input('jor_no');
                    // $rfq_m['remaining_qty']=$rm->remaining_qty;
                    $rfq_m['created_at']=date('Y-m-d H:i:s');
                    $jo_rfq_material_details_id=JORFQMaterialDetails::insertGetId($rfq_m);

                    // $update_status = PrReportDetails::where('pr_details_id','=', $rm->pr_details_id)->update(['status' => 'For Canvass']);

                    for($m=0;$m<3;$m++){
                        JORFQMaterialOffers::create([
                            'offer_no'=>$m+1,
                            'jo_rfq_head_id'=>$jo_rfq_head_id,
                            'jo_rfq_vendor_id'=>$jo_rfq_vendor_id,
                            'jo_rfq_material_details_id'=>$jo_rfq_material_details_id,
                            'jor_material_details_id'=>$rm->jor_material_details_id,
                            'jor_no'=>$request->input('jor_no'),
                            // 'remaining_qty'=>$rm->remaining_qty,
                            'uom'=>$rm->uom,
                        ]);
                    }
                }
            }
            
            return $jo_rfq_head_id;

        }

        public function get_jo_rfq_data($jo_rfq_head_id, $start = 'a', $count = 26){
            $letters = [];
            $startAscii = ord($start);
    
            for ($i = 0; $i < $count; $i++) {
                $letters[] = chr($startAscii + $i);
            }

            $userid = Auth::id();
            $signatories=User::where('id','!=',$userid)->orderBy('name','ASC')->get()->unique('name');
            $jo_rfq_vendor_terms = JORFQTerms::whereIn('jo_rfq_vendor_id',JORFQVendor::where('jo_rfq_head_id',$jo_rfq_head_id)->pluck('id'))->orderBy('id','ASC')->get();
            $rfqmaterials =JORMaterialDetails::whereNotIn('id',JORFQMaterialDetails::where('jo_rfq_head_id',$jo_rfq_head_id)->pluck('jor_material_details_id'))->where('status','Saved')->get();
            $count_jormaterial=$rfqmaterials->count();
            $rfqlabor =JORLaborDetails::whereNotIn('id',JORFQLaborDetails::where('jo_rfq_head_id',$jo_rfq_head_id)->pluck('jor_labor_details_id'))->where('status','Saved')->get();
            $count_jorlabor=$rfqlabor->count();
            $currency=Config::get('constants.currency');
            $jo_rfq_head = JORFQHead::with('jor_head')->where('id',$jo_rfq_head_id)->get();

            $canvass_complete_rfq =JORFQVendor::where('jo_rfq_head_id',$jo_rfq_head_id)->where('canvassed',1)->get();
            $count_ccr=$canvass_complete_rfq->count();
            foreach($jo_rfq_head AS $h){
                $jor_head_notes = JORNotes::where('jor_head_id',$h->jor_head->id)->get();
                $RFQHead = [
                    'jor_no'=>$h->jor_no,
                    'rfq_no'=>$h->rfq_no,
                    'rfq_name'=>$h->rfq_name,
                    'rfq_date'=>date('F j, Y', strtotime($h->rfq_date)),
                    'site_jor'=>$h->jor_head->site_jor,
                    'location'=>$h->jor_head->location,
                    'date_prepared'=>date('F j, Y', strtotime($h->jor_head->date_prepared)),
                    'department_name'=>$h->jor_head->department_name,
                    'enduse'=>$h->jor_head->enduse,
                    'purpose'=>$h->jor_head->purpose,
                    'process_code'=>$h->jor_head->process_code,
                    'urgency'=>$h->jor_head->urgency,
                    'general_description'=>$h->jor_head->general_description,
                    'project_activity'=>$h->jor_head->project_activity,
                    'preparedby_id'=>$userid,
                    'prepared_by_name'=>User::where('id',$userid)->value('name')
                ];
            }

            $jo_rfq_vendor = JORFQVendor::with('vendor_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->orderBy('vendor_name','ASC')->get();
            $jorfqvendorid=JORFQVendor::where('jo_rfq_head_id',$jo_rfq_head_id)->first()->id;
                foreach($jo_rfq_vendor AS $v){
                    $jorfq_vendor_terms = JORFQTerms::whereIn('jo_rfq_vendor_id',JORFQVendor::where('jo_rfq_head_id',$jo_rfq_head_id)->pluck('id'))->where('jo_rfq_vendor_id',$v->id)->orderBy('id','ASC')->get();
                    // $jorfq_vendorterms = JORFQTerms::where('jo_rfq_vendor_id',$v->id)->orderBy('id','ASC')->get();
                    // $count_rfq_terms=$jorfq_vendorterms->count();

                    $vendor_labor_offers = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('jo_rfq_vendor_id',$v->id)->where('offer','!=','')->get();
                    $count_vendor_labor_offers=$vendor_labor_offers->count();

                    $vendor_material_offers = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('jo_rfq_vendor_id',$v->id)->where('offer','!=','')->get();
                    $count_vendor_material_offers=$vendor_material_offers->count();

                    $RFQVendor[] = [
                        'jo_rfq_vendor_id'=>$v->id,
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
                        'jorfq_vendor_terms'=>$jorfq_vendor_terms,
                        // 'count_rfq_terms'=>$count_rfq_terms,
                        // 'count_vendor_labor_offers'=>$vendor_labor_offers,
                        // 'count_vendor_material_offers'=>$count_vendor_material_offers,
                    ];
                }

            $jo_rfq_labor_details = JORFQLaborDetails::with('jor_labor_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->get();
                foreach($jo_rfq_labor_details AS $jrld){
                    $RFQLaborDetails[] = [
                        'jo_rfq_labor_details_id'=>$jrld->id,
                        'jo_rfq_vendor_id'=>$jrld->jo_rfq_vendor_id,
                        'jor_rfq_labor_details_id'=>$jrld->jor_rfq_labor_details_id,
                        'quantity'=>$jrld->jor_labor_details->quantity,
                        // 'remaining_qty'=>$jrld->remaining_qty,
                        'scope_of_work'=>$jrld->jor_labor_details->scope_of_work,
                    ];

                $rfq_labor_offers = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('jo_rfq_labor_details_id',$jrld->id)->get();
                    foreach($rfq_labor_offers AS $rlo){
                        $RFQLaborOffers[] = [
                            'jo_rfq_labor_offer_id'=>$rlo->id,
                            'jo_rfq_labor_details_id'=>$rlo->jo_rfq_labor_details_id,
                            'offer'=>$rlo->offer,
                            'unit_price'=>$rlo->unit_price,
                            'labor_currency'=>$rlo->currency ?? 'PHP',
                        ];
                    }
                }

            $jo_rfq_material_details = JORFQMaterialDetails::with('jor_material_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->get();
                foreach($jo_rfq_material_details AS $jrld){
                    $RFQMaterialDetails[] = [
                        'jo_rfq_material_details_id'=>$jrld->id,
                        'jo_rfq_vendor_id'=>$jrld->jo_rfq_vendor_id,
                        'jor_rfq_material_details_id'=>$jrld->jor_rfq_material_details_id,
                        'quantity'=>$jrld->jor_material_details->quantity,
                        // 'remaining_qty'=>$jrld->remaining_qty,
                        'item_description'=>$jrld->jor_material_details->item_description,
                    ];

                $rfq_material_offers = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('jo_rfq_material_details_id',$jrld->id)->get();
                    foreach($rfq_material_offers AS $rmo){
                        $RFQMaterialOffers[] = [
                            'jo_rfq_material_offer_id'=>$rmo->id,
                            'jo_rfq_material_details_id'=>$rmo->jo_rfq_material_details_id,
                            'offer'=>$rmo->offer,
                            'unit_price'=>$rmo->unit_price,
                            'material_currency'=>$rmo->currency ?? 'PHP',
                        ];
                    }
                }

            return response()->json([
                'head'=>$RFQHead,
                'jor_head_notes'=>$jor_head_notes,
                'rfq_vendor'=>$RFQVendor,
                'rfq_labor_details'=>$RFQLaborDetails,
                'rfq_labor_offers'=>$RFQLaborOffers,
                'rfq_material_details'=>$RFQMaterialDetails,
                'rfq_material_offers'=>$RFQMaterialOffers,
                'rfq_vendor_terms'=>$jo_rfq_vendor_terms,
                'signatories'=>$signatories,
                'count_jorlabor'=>$count_jorlabor,
                'count_jormaterial'=>$count_jormaterial,
                'currency'=>$currency,
                'letters'=>$letters,
                'count_ccr'=>$count_ccr,
                'rfqvendor_id'=>$jorfqvendorid,
                'count_vendor_labor_offers'=>$count_vendor_labor_offers,
                'count_vendor_material_offers'=>$count_vendor_material_offers,
            ],200);
        }

        public function save_print_jo_rfq_details(Request $request){
            $jo_rfq_vendor_id = $request->input('jo_rfq_vendor_id');
            $update_data=JORFQVendor::where('id',$jo_rfq_vendor_id)->first();
            $update_data->update([
                'prepared_by'=>$request->input('prepared_by'),
                'noted_by'=>$request->input('noted_by'),
                'approved_by'=>$request->input('approved_by'),
            ]);
        }

        public function add_additional_jo_terms(Request $request){
            $new_rfq_terms['jo_rfq_vendor_id']=$request->input('jo_rfq_vendor_id');
            $new_rfq_terms['terms']=$request->input('terms');
            JORFQTerms::create($new_rfq_terms);
        }

        public function update_jo_terms(Request $request){
            $jo_rfq_terms_id = $request->input('jo_rfq_terms_id');
            $update_rfq_terms=JORFQTerms::where('id',$jo_rfq_terms_id)->update([
                'terms'=>$request->input('terms'),
            ]);
        }

        public function remove_jo_terms($jo_rfq_terms_id){
            if(JORFQTerms::where('id','=',$jo_rfq_terms_id)->exists()){
            $rfqterms=JORFQTerms::find($jo_rfq_terms_id);
            $rfqterms->delete(); 
            }
        }

        public function jo_rfq_vendor_list_data($jo_rfq_head_id){
            $vendor_list =VendorDetails::with('vendor_head')->whereNotIn('id',JORFQVendor::where('jo_rfq_head_id',$jo_rfq_head_id)->pluck('vendor_details_id'))->where('status','Active')->orderBy('address','ASC')->get();
            return response()->json($vendor_list);
        }

        public function add_additional_jo_rfq_vendor(Request $request){
            $jo_rfq_head_id = $request->input('jo_rfq_head_id');

            $rfq_add_vendor['jo_rfq_head_id']=$jo_rfq_head_id;
            $rfq_add_vendor['jor_no']=$request->input('jor_no');
            $rfq_add_vendor['vendor_details_id']=$request->input('vendor_details_id');
            $rfq_add_vendor['vendor_name']=$request->input('vendor_name');
            $rfq_add_vendor['vendor_identifier']=$request->input('vendor_identifier');
            $rfq_add_vendor['created_at']=date('Y-m-d H:i:s');
            $jo_rfq_vendor_id=JORFQVendor::insertGetId($rfq_add_vendor);

            $vendorterms = VendorTerms::where('vendor_details_id',$request->input('vendor_details_id'))->get();
            foreach($vendorterms as $vt){
                    $new_rfq_terms['jo_rfq_vendor_id']=$jo_rfq_vendor_id;
                    $new_rfq_terms['terms']=$vt->terms;
                    JORFQTerms::create($new_rfq_terms);
            }
            
            $jo_rfq_labor_details = JORFQLaborDetails::with('jor_labor_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->where('status',null)->get()->unique('jor_labor_details_id');
            foreach($jo_rfq_labor_details AS $jrld){
                // $deliver_qty = PrReportDetails::where('jor_labor_details_id',$jrld->jor_labor_details_id)->value('delivered_qty');
                // $remaining_qty = $jrld->jor_labor_details->quantity - $deliver_qty;

                $rfq_l['jo_rfq_head_id']=$jo_rfq_head_id;
                $rfq_l['jo_rfq_vendor_id']=$jo_rfq_vendor_id;
                $rfq_l['jor_labor_details_id']=$jrld->jor_labor_details_id;
                $rfq_l['jor_no']=$request->input('jor_no');
                // $rfq_l['remaining_qty']=$remaining_qty;
                $rfq_l['created_at']=date('Y-m-d H:i:s');
                $jo_rfq_labor_details_id=JORFQLaborDetails::insertGetId($rfq_l);

                for($l=0;$l<3;$l++){
                    JORFQLaborOffers::create([
                        'offer_no'=>$l+1,
                        'jo_rfq_head_id'=>$jo_rfq_head_id,
                        'jo_rfq_vendor_id'=>$jo_rfq_vendor_id,
                        'jo_rfq_labor_details_id'=>$jo_rfq_labor_details_id,
                        'jor_labor_details_id'=>$jrld->jor_labor_details_id,
                        'jor_no'=>$request->input('jor_no'),
                        // 'remaining_qty'=>$remaining_qty,
                        'uom'=>$jrld->jor_labor_details->uom,
                    ]);
                }
            }

            $jo_rfq_material_details = JORFQMaterialDetails::with('jor_material_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->where('status',null)->get()->unique('jor_material_details_id');
            foreach($jo_rfq_material_details AS $jrmd){
                // $deliver_qty = PrReportDetails::where('jor_material_details_id',$jrmd->jor_material_details_id)->value('delivered_qty');
                // $remaining_qty = $jrmd->jor_material_details->quantity - $deliver_qty;

                $rfq_m['jo_rfq_head_id']=$jo_rfq_head_id;
                $rfq_m['jo_rfq_vendor_id']=$jo_rfq_vendor_id;
                $rfq_m['jor_material_details_id']=$jrmd->jor_material_details_id;
                $rfq_m['jor_no']=$request->input('jor_no');
                // $rfq_m['remaining_qty']=$remaining_qty;
                $rfq_m['created_at']=date('Y-m-d H:i:s');
                $jo_rfq_material_details_id=JORFQMaterialDetails::insertGetId($rfq_m);

                
                for($m=0;$m<3;$m++){
                    JORFQMaterialOffers::create([
                        'offer_no'=>$m+1,
                        'jo_rfq_head_id'=>$jo_rfq_head_id,
                        'jo_rfq_vendor_id'=>$jo_rfq_vendor_id,
                        'jo_rfq_material_details_id'=>$jo_rfq_material_details_id,
                        'jor_material_details_id'=>$jrmd->jor_material_details_id,
                        'jor_no'=>$request->input('jor_no'),
                        // 'remaining_qty'=>$remaining_qty,
                        'uom'=>$jrmd->jor_material_details->uom,
                    ]);
                }
            }

        }

        public function get_jo_rfq_item_list($jo_rfq_head_id){
            $jor_head_id = JORFQHead::where('id',$jo_rfq_head_id)->value('jor_head_id');

            $jorlabor_list =JORLaborDetails::where('jor_head_id', $jor_head_id)->whereNotIn('id',JORFQLaborDetails::where('jo_rfq_head_id',$jo_rfq_head_id)->pluck('jor_labor_details_id'))->where('status','Saved')->orderBy('scope_of_work','ASC')->get();
                $jor_labor_list=array();
                    foreach($jorlabor_list AS $jorl){
                        // $deliver_qty = PrReportDetails::where('jor_labor_details_id',$jorl->id)->value('delivered_qty');
                        // $remaining_qty = $jorl->quantity - $deliver_qty;
                        // if($remaining_qty != 0){
                            $jor_labor_list[] = [
                                'labor_checkbox'=>0,
                                'jor_labor_details_id'=>$jorl->id,
                                'date_needed'=>$jorl->date_needed,
                                'scope_of_work'=>$jorl->scope_of_work,
                                'quantity'=>$jorl->quantity,
                                // 'remaining_qty'=>$remaining_qty,
                                'uom'=>$jorl->uom,
                            ];
                        // }
                    }

            $jormaterial_list =JORMaterialDetails::where('jor_head_id', $jor_head_id)->whereNotIn('id',JORFQMaterialDetails::where('jo_rfq_head_id',$jo_rfq_head_id)->pluck('jor_material_details_id'))->where('status','Saved')->orderBy('item_description','ASC')->get();
                $jor_material_list=array();
                    foreach($jormaterial_list AS $jorm){
                        // $deliver_qty = PrReportDetails::where('jor_labor_details_id',$jorm->id)->value('delivered_qty');
                        // $remaining_qty = $jorm->quantity - $deliver_qty;
                        // if($remaining_qty != 0){
                            $jor_material_list[] = [
                                'material_checkbox'=>0,
                                'jor_material_details_id'=>$jorm->id,
                                'date_needed'=>$jorm->date_needed,
                                'item_description'=>$jorm->item_description,
                                'quantity'=>$jorm->quantity,
                                // 'remaining_qty'=>$remaining_qty,
                                'uom'=>$jorm->uom,
                                'pn_no'=>$jorm->pn_no,
                                'wh_stocks'=>$jorm->wh_stocks,
                            ];
                        // }
                    }

            return response()->json([
                'jor_labor_list'=>$jor_labor_list,
                'jor_material_list'=>$jor_material_list,
            ],200);
        }

        public function add_additional_labor_material(Request $request){
            $jo_rfq_head_id = $request->input('jo_rfq_head_id');
            $additional_labor = $request->input('additional_labor');
            $additional_material = $request->input('additional_material');
                
            $jo_rfq_vendors =JORFQVendor::with('vendor_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->get();
                foreach($jo_rfq_vendors as $jrv){
                    foreach(json_decode($additional_labor) as $al){
                        if($al->labor_checkbox != 0){
                            $rfq_l['jo_rfq_head_id']=$jo_rfq_head_id;
                            $rfq_l['jo_rfq_vendor_id']=$jrv->id;
                            $rfq_l['jor_labor_details_id']=$al->jor_labor_details_id;
                            $rfq_l['jor_no']=$request->input('jor_no');
                            // $rfq_l['remaining_qty']=$al->remaining_qty;
                            $rfq_l['created_at']=date('Y-m-d H:i:s');
                            $jo_rfq_labor_details_id=JORFQLaborDetails::insertGetId($rfq_l);
    
                            // $update_status = PrReportDetails::where('jor_labor_details_id','=', $al->jor_labor_details_id)->update(['status' => 'For Canvass']);

                            for($l=0;$l<3;$l++){
                                JORFQLaborOffers::create([
                                    'offer_no'=>$l+1,
                                    'jo_rfq_head_id'=>$jo_rfq_head_id,
                                    'jo_rfq_vendor_id'=>$jrv->id,
                                    'jo_rfq_labor_details_id'=>$jo_rfq_labor_details_id,
                                    'jor_labor_details_id'=>$al->jor_labor_details_id,
                                    'jor_no'=>$request->input('jor_no'),
                                    // 'remaining_qty'=>$ai->remaining_qty,
                                    'uom'=>$al->uom,
                                ]);
                            }
                        }
                    }

                    foreach(json_decode($additional_material) as $am){
                        if($am->material_checkbox != 0){
                            $rfq_m['jo_rfq_head_id']=$jo_rfq_head_id;
                            $rfq_m['jo_rfq_vendor_id']=$jrv->id;
                            $rfq_m['jor_material_details_id']=$am->jor_material_details_id;
                            $rfq_m['jor_no']=$request->input('jor_no');
                            // $rfq_m['remaining_qty']=$ai->remaining_qty;
                            $rfq_m['created_at']=date('Y-m-d H:i:s');
                            $jo_rfq_material_details_id=JORFQMaterialDetails::insertGetId($rfq_m);
    
                            // $update_status = PrReportDetails::where('jor_material_details_id','=', $am->jor_material_details_id)->update(['status' => 'For Canvass']);
                            
                            for($m=0;$m<3;$m++){
                                JORFQMaterialOffers::create([
                                    'offer_no'=>$m+1,
                                    'jo_rfq_head_id'=>$jo_rfq_head_id,
                                    'jo_rfq_vendor_id'=>$jrv->id,
                                    'jo_rfq_material_details_id'=>$jo_rfq_material_details_id,
                                    'jor_material_details_id'=>$am->jor_material_details_id,
                                    'jor_no'=>$request->input('jor_no'),
                                    // 'remaining_qty'=>$ai->remaining_qty,
                                    'uom'=>$am->uom,
                                ]);
                            }
                        }
                    }
                }
        }

        public function canvass_complete_jo_vendor(Request $request){
            $userid = Auth::id();
            $jo_rfq_head_id = $request->input('jo_rfq_head_id');
            $jo_rfq_vendor_id = $request->input('jo_rfq_vendor_id');
            $laboroffers = $request->input('laboroffers');
            $materialoffers = $request->input('materialoffers');
            foreach(json_decode($laboroffers) as $lo){
                if(JORFQLaborOffers::where('id','=',$lo->jo_rfq_labor_offer_id)->exists()){
                    $update_labor_offer = JORFQLaborOffers::find($lo->jo_rfq_labor_offer_id);
                    $update_labor_offer->offer = $lo->offer;
                    $update_labor_offer->unit_price = $lo->unit_price;
                    $update_labor_offer->currency = $lo->labor_currency;
                    $update_labor_offer->save();
                }
            }

            foreach(json_decode($materialoffers) as $mo){
                if(JORFQMaterialOffers::where('id','=',$mo->jo_rfq_material_offer_id)->exists()){
                    $update_material_offer = JORFQMaterialOffers::find($mo->jo_rfq_material_offer_id);
                    $update_material_offer->offer = $mo->offer;
                    $update_material_offer->unit_price = $mo->unit_price;
                    $update_material_offer->currency = $mo->material_currency;
                    $update_material_offer->save();
                }
            }


            $jorfqvendor=JORFQVendor::where('id',$jo_rfq_vendor_id)->first();
            $data = [
                'canvassed' => "1",
                'canvassed_by' => $userid,
                'canvassed_date' => date('Y-m-d H:i:s'),
                
            ];
            $jorfqvendor->update($data);

            $update_status = JORFQVendor::where('jo_rfq_head_id','=', $jo_rfq_head_id)->where('id','=', $jo_rfq_vendor_id)->update(['status' => 'Saved']);
        }

        public function draft_jo_vendor(Request $request){
            $jo_rfq_head_id = $request->input('jo_rfq_head_id');
            $jo_rfq_vendor_id = $request->input('jo_rfq_vendor_id');
            $laboroffers = $request->input('laboroffers');
            $materialoffers = $request->input('materialoffers');
            foreach(json_decode($laboroffers) as $lo){
                if(JORFQLaborOffers::where('id','=',$lo->jo_rfq_labor_offer_id)->exists()){
                    $update_labor_offer = JORFQLaborOffers::find($lo->jo_rfq_labor_offer_id);
                    $update_labor_offer->offer = $lo->offer;
                    $update_labor_offer->unit_price = $lo->unit_price;
                    $update_labor_offer->currency = $lo->labor_currency;
                    $update_labor_offer->save();
                }
            }

            foreach(json_decode($materialoffers) as $mo){
                if(JORFQMaterialOffers::where('id','=',$mo->jo_rfq_material_offer_id)->exists()){
                    $update_material_offer = JORFQMaterialOffers::find($mo->jo_rfq_material_offer_id);
                    $update_material_offer->offer = $mo->offer;
                    $update_material_offer->unit_price = $mo->unit_price;
                    $update_material_offer->currency = $mo->material_currency;
                    $update_material_offer->save();
                }
            }
            
            $update_status = JORFQVendor::where('jo_rfq_head_id','=', $jo_rfq_head_id)->where('id','=', $jo_rfq_vendor_id)->update(['status' => 'Draft']);
        }

}
