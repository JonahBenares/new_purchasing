<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\POHead;
use App\Models\PoDetails;
use App\Models\POSeries;
use App\Models\VendorHead;
use App\Models\VendorDetails;
use App\Models\RFQVendor;
use App\Models\AOQHead;
use App\Models\AOQDetails;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Config;
class POController extends Controller
{
    public function supplier_dropdown(){
        $suppliers = VendorDetails::select('vendor_details.id','identifier','vendor_name')->join('vendor_head', 'vendor_head.id', '=', 'vendor_details.vendor_head_id')->where('status','=','Active')->get();
        return response()->json([
            'suppliers'=>$suppliers,
        ],200);
    }
}
