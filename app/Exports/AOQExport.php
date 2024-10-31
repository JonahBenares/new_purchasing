<?php

namespace App\Exports;

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
use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AOQExport implements FromView
{

         /**
    * @return \Illuminate\Support\Collection
    */

    protected $aoq_head_id;
    // use Exportable;

    public function __construct($aoq_head_id) {
        $this->aoq_head_id = $aoq_head_id;
    }

    public function view(): View
    {   
        $aoq_head_id=$this->aoq_head_id;
        $start='a';
        $count = 26;
        $letters = [];
        $startAscii = ord($start);

        for ($i = 0; $i < $count; $i++) {
            $letters[] = chr($startAscii + $i);
        }
        $rfq_head_id = AOQHead::where('id',$this->aoq_head_id)->value('rfq_head_id');
        $aoq_details = AOQDetails::with('rfq_vendor')->where('aoq_head_id',$aoq_head_id)->get();
        foreach($aoq_details AS $ad){
            // $min_price = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('pr_details_id',$ad->pr_details_id)->min('unit_price');
            $vendor_data[] = [
                'rfq_vendor_id'=>$ad->rfq_vendor_id,
                'vendor_name'=>$ad->rfq_vendor->vendor_name,
                'vendor_identifier'=>$ad->rfq_vendor->vendor_identifier,
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

        $allterms =RFQVendorTerms::whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->orderBy('id','ASC')->get();
        foreach($allterms AS $at){
            $all_terms[] = [
                'terms_id'=>$at->id,
                'rfq_vendor_id'=>$at->rfq_vendor_id,
                'terms'=>$at->terms,
            ];
        }

        $aoq_items = RFQDetails::with('pr_details')->where('rfq_head_id',$rfq_head_id)->get()->unique('pr_details_id');
        foreach($aoq_items AS $ai){
            $min_price = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('pr_details_id',$ai->pr_details_id)->where('unit_price','!=',0)->whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->min('unit_price');
            $items_data[] = [
                'rfq_details_id'=>$ai->id,
                'pr_details_id'=>$ai->pr_details_id,
                'item_description'=>$ai->pr_details->item_description,
                'uom'=>$ai->pr_details->uom,
                'quantity'=>$ai->pr_details->quantity,
                'rfq_vendor_id'=>$ai->rfq_vendor_id,
                'min_price'=>$min_price,
            ];

            $rfq_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('rfq_vendor_id',$ai->rfq_vendor_id)->get();
            foreach($rfq_offers AS $o){
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
        }

        // $first_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('offer_no',1)->get();
        // $second_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('offer_no',2)->get();
        // $third_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->where('offer_no',3)->get();
        $first_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->where('offer_no',1)->get();
        $second_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->where('offer_no',2)->get();
        $third_offers = RFQOffers::where('rfq_head_id',$rfq_head_id)->whereIn('rfq_vendor_id',AOQDetails::where('aoq_head_id',$aoq_head_id)->pluck('rfq_vendor_id'))->where('offer_no',3)->get();

        $rfq_no = RFQHead::where('id',$rfq_head_id)->value('rfq_no');
        $pr_no = AOQHead::where('id',$this->aoq_head_id)->value('pr_no');
        $prepared_by = AOQHead::where('id',$this->aoq_head_id)->value('prepared_by');
        $received_by = AOQHead::where('id',$this->aoq_head_id)->value('received_by');
        $aoq_date = AOQHead::where('id',$this->aoq_head_id)->value('aoq_date');
        $date_needed = AOQHead::where('id',$this->aoq_head_id)->value('date_needed');
        $award_recommended_by = AOQHead::where('id',$this->aoq_head_id)->value('award_recommended_by');
        $recommended_by = AOQHead::where('id',$this->aoq_head_id)->value('recommended_by');
        $approved_by = AOQHead::where('id',$this->aoq_head_id)->value('approved_by');

        // Return the view and pass the data
        return view('aoq_format', [
            'aoq_no'=>AOQHead::where('id',$this->aoq_head_id)->value('aoq_no'),
            'status'=>AOQHead::where('id',$this->aoq_head_id)->value('status'),
            'aoq_status'=>AOQHead::where('id',$this->aoq_head_id)->value('aoq_status'),
            'aoq_date'=>date('F j, Y', strtotime($aoq_date)),
            'rfq_no'=>$rfq_no,
            'pr_no'=>$pr_no,
            'department'=>PRHead::where('pr_no',$pr_no)->value('department_name'),
            'enduse'=>PRHead::where('pr_no',$pr_no)->value('enduse'),
            'purpose'=>PRHead::where('pr_no',$pr_no)->value('purpose'),
            'requestor'=>PRHead::where('pr_no',$pr_no)->value('requestor'),
            'date_needed'=>date('F j, Y', strtotime($date_needed)),
            'aoq_vendor_data'=>$vendor_data,
            'aoq_items_data'=>$items_data,
            'rfq_offers'=>$rfq_offers,
            'first_offers'=>$first_offers,
            'second_offers'=>$second_offers,
            'third_offers'=>$third_offers,
            'vendor_terms'=>$vendor_terms,
            'all_terms'=>$all_terms,
            // 'letters'=>$letters,
            'prepared_by_name'=>User::where('id',$prepared_by)->value('name'),
            'received_by_name'=>User::where('id',$received_by)->value('name'),
            'award_recommended_by_name'=>User::where('id',$award_recommended_by)->value('name'),
            'recommended_by_name'=>User::where('id',$recommended_by)->value('name'),
            'approved_by_name'=>User::where('id',$approved_by)->value('name'),
        ]);
    }
}
