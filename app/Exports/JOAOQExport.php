<?php

namespace App\Exports;

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
use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JOAOQExport implements FromView
{

         /**
    * @return \Illuminate\Support\Collection
    */

    protected $jo_aoq_head_id;
    // use Exportable;

    public function __construct($jo_aoq_head_id) {
        $this->jo_aoq_head_id = $jo_aoq_head_id;
    }

    public function view(): View
    {   
        $jo_aoq_head_id=$this->jo_aoq_head_id;
        $start='a';
        $count = 26;
        $letters = [];
        $startAscii = ord($start);

        for ($i = 0; $i < $count; $i++) {
            $letters[] = chr($startAscii + $i);
        }
        $jo_rfq_head_id = JOAOQHead::where('id',$this->jo_aoq_head_id)->value('jo_rfq_head_id');
        $aoq_details = JOAOQDetails::with('jo_rfq_vendor')->where('jo_aoq_head_id',$jo_aoq_head_id)->get();
        $count_aoq_vendors =$aoq_details->count();
        $colspan = 4 + ($count_aoq_vendors * 4);
        foreach($aoq_details AS $ad){
            $vendor_data[] = [
                'jo_rfq_vendor_id'=>$ad->jo_rfq_vendor_id,
                'vendor_name'=>$ad->jo_rfq_vendor->vendor_name,
                'vendor_identifier'=>$ad->jo_rfq_vendor->vendor_identifier,
                'contact_person'=>VendorDetails::where('id',$ad->jo_rfq_vendor->vendor_details_id)->value('contact_person'),
                'phone'=>VendorDetails::where('id',$ad->jo_rfq_vendor->vendor_details_id)->value('phone'),
            ];
        }

        $allterms =JORFQTerms::whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->orderBy('id','ASC')->get();
        foreach($allterms AS $at){
            $all_terms[] = [
                'terms_id'=>$at->id,
                'jo_rfq_vendor_id'=>$at->jo_rfq_vendor_id,
                'terms'=>$at->terms,
            ];
        }

        $aoq_labor = JORFQLaborDetails::with('jor_labor_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->get()->unique('jor_labor_details_id');
        foreach($aoq_labor AS $al){
            $min_price = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('jor_labor_details_id',$al->jor_labor_details_id)->where('unit_price','!=',0)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->min('unit_price');
            $labor_data[] = [
                'jo_rfq_labor_details_id'=>$al->id,
                'jor_labor_details_id'=>$al->jor_labor_details_id,
                'scope_of_work'=>$al->jor_labor_details->scope_of_work,
                'uom'=>$al->jor_labor_details->uom,
                'quantity'=>$al->jor_labor_details->quantity,
                'jo_rfq_vendor_id'=>$al->jo_rfq_vendor_id_vendor_id,
                'min_price'=>$min_price,
            ];

            $rfq_labor_offers = JORFQLaborOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('jor_labor_details_id',$al->jor_labor_details_id)->get();
            foreach($rfq_labor_offers AS $rlo){
                $labor_offers[] = [
                    'min_price'=>$min_price,
                    'jo_rfq_labor_offer_id'=>$rlo->id,
                    'jo_rfq_labor_details_id'=>$rlo->rfq_details_id,
                    'jor_labor_details_id'=>$rlo->jor_labor_details_id,
                    'jo_rfq_vendor_id'=>$rlo->rfq_vendor_id,
                    'offer'=>$rlo->offer,
                    'unit_price'=>$rlo->unit_price,
                    'labor_currency'=>$rlo->currency,
                    'awarded'=>$rlo->awarded,
                    'comments'=>$rlo->remarks,
                ];
            }
        }

        $aoq_material = JORFQMaterialDetails::with('jor_material_details')->where('jo_rfq_head_id',$jo_rfq_head_id)->get()->unique('jor_material_details_id');
        foreach($aoq_material AS $am){
            $min_price = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->where('jor_material_details_id',$am->jor_material_details_id)->where('unit_price','!=',0)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->min('unit_price');
            $material_data[] = [
                'jo_rfq_material_details_id'=>$am->id,
                'jor_material_details_id'=>$am->jor_material_details_id,
                'item_description'=>$am->jor_material_details->item_description,
                'uom'=>$am->jor_material_details->uom,
                'quantity'=>$am->jor_material_details->quantity,
                'jo_rfq_vendor_id'=>$am->jo_rfq_vendor_id_vendor_id,
                'min_price'=>$min_price,
            ];
        }

        $first_offers = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('offer_no',1)->get();
        $second_offers = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('offer_no',2)->get();
        $third_offers = JORFQMaterialOffers::where('jo_rfq_head_id',$jo_rfq_head_id)->whereIn('jo_rfq_vendor_id',JOAOQDetails::where('jo_aoq_head_id',$jo_aoq_head_id)->pluck('jo_rfq_vendor_id'))->where('offer_no',3)->get();

        $rfq_no = JORFQHead::where('id',$jo_rfq_head_id)->value('rfq_no');
        $jor_no = JOAOQHead::where('id',$this->jo_aoq_head_id)->value('jor_no');
        $prepared_by = JOAOQHead::where('id',$this->jo_aoq_head_id)->value('prepared_by');
        $received_by = JOAOQHead::where('id',$this->jo_aoq_head_id)->value('received_by');
        $aoq_date = JOAOQHead::where('id',$this->jo_aoq_head_id)->value('aoq_date');
        $date_needed = JOAOQHead::where('id',$this->jo_aoq_head_id)->value('date_needed');
        $award_recommended_by = JOAOQHead::where('id',$this->jo_aoq_head_id)->value('award_recommended_by');
        $recommended_by = JOAOQHead::where('id',$this->jo_aoq_head_id)->value('recommended_by');
        $approved_by = JOAOQHead::where('id',$this->jo_aoq_head_id)->value('approved_by');

        // Return the view and pass the data
        return view('jo_aoq_format', [
            'aoq_no'=>JOAOQHead::where('id',$this->jo_aoq_head_id)->value('aoq_no'),
            'status'=>JOAOQHead::where('id',$this->jo_aoq_head_id)->value('status'),
            'aoq_status'=>JOAOQHead::where('id',$this->jo_aoq_head_id)->value('aoq_status'),
            'aoq_date'=>date('F j, Y', strtotime($aoq_date)),
            'rfq_no'=>$rfq_no,
            'jor_no'=>$jor_no,
            'department'=>JORHead::where('jor_no',$jor_no)->value('department_name'),
            'project_activity'=>JORHead::where('jor_no',$jor_no)->value('project_activity'),
            'general_description'=>JORHead::where('jor_no',$jor_no)->value('general_description'),
            'purpose'=>JORHead::where('jor_no',$jor_no)->value('purpose'),
            'requestor'=>JORHead::where('jor_no',$jor_no)->value('requestor'),
            'date_needed'=>date('F j, Y', strtotime($date_needed)),
            'aoq_vendor_data'=>$vendor_data,
            'aoq_labor_data'=>$labor_data,
            'aoq_material_data'=>$material_data,
            'labor_offers'=>$labor_offers,
            'first_offers'=>$first_offers,
            'second_offers'=>$second_offers,
            'third_offers'=>$third_offers,
            'all_terms'=>$all_terms,
            // 'letters'=>$letters,
            'prepared_by_name'=>User::where('id',$prepared_by)->value('name'),
            'received_by_name'=>User::where('id',$received_by)->value('name'),
            'award_recommended_by_name'=>User::where('id',$award_recommended_by)->value('name'),
            'recommended_by_name'=>User::where('id',$recommended_by)->value('name'),
            'approved_by_name'=>User::where('id',$approved_by)->value('name'),
            'colspan'=>$colspan,
        ]);
    }
}
