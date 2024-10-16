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
            $pr_head_id  = RFQHead::where('id',$aa->rfq_head_id)->value('pr_head_id');
            $department_name= PRHead::where('id',$pr_head_id)->value('department_name');
            $enduse= PRHead::where('id',$pr_head_id)->value('enduse');
            $purpose= PRHead::where('id',$pr_head_id)->value('purpose');
            $requestor= PRHead::where('id',$pr_head_id)->value('requestor');
            $all_vendors = RFQVendor::with('vendor_details')->whereIn('id',AOQDetails::where('aoq_head_id',$aa->id)->pluck('rfq_vendor_id'))->orderby('vendor_name', 'ASC')->get();
            $aoqarray[]=[
                'id'=>$aa->id,
                'aoq_status'=>$aa->aoq_status,
                'vendor'=>$all_vendors,
                date('F j, Y', strtotime($aa->aoq_date)),
                $aa->pr_no,
                '',
                $department_name,
                $enduse,
                // $purpose,
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

    public function get_rfq_data($rfq_head_id){
        
    }
}
