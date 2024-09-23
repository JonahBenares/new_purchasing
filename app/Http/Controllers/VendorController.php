<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorHead;
use App\Models\VendorDetails;

class VendorController extends Controller
{
    public function get_all_vendors(Request $request){
        $all_vendor = VendorHead::orderby('vendor_name', 'ASC')->get();
        $x=0;
        $vendorarray=array();
        foreach($all_vendor AS $av){
            $branch=VendorDetails::where('vendor_head_id',$av->id)->get();
            $no_of_branches=$branch->count();
            $vendorarray[]=[
                'id'=>$av->id,
                $av->vendor_name,
                $av->product_services,
                $no_of_branches,
                ''
            ];
            $x++;
        }
        return response()->json([
            'vendorarray'=>$vendorarray,
        ],200);
    }  

    public function add_vendor(Request $request){
        // $validated=$this->validate($request,[
        //     'vendor_name'=>['unique:vendor_head','required','string'],
        //     'product_services'=>['string']
        // ]);
        // $vendor_head_id=VendorHead::insertGetId($validated);
        $vendorhead['vendor_name']=$request->input('vendor_name');
        $vendorhead['product_services']=$request->input('product_services');
        $vendor_head_id=VendorHead::insertGetId($vendorhead);

        $branch_list = $request->input('vendor_branches');
        foreach(json_decode($branch_list) as $bl){
            $branch_details['vendor_head_id']=$vendor_head_id;
            $branch_details['address']=$bl->address;
            $branch_details['identifier']=$bl->identifier;
            $branch_details['terms']=$bl->terms;
            $branch_details['phone']=$bl->phone;
            $branch_details['fax']=$bl->fax;
            $branch_details['contact_person']=$bl->contact_person;
            $branch_details['email']=$bl->email;
            $branch_details['tin']=$bl->tin;
            $branch_details['type']=$bl->type;
            $branch_details['notes']=$bl->notes;
            $branch_details['ewt']=$bl->ewt;
            $branch_details['vat']=($bl->vat == 'Vat') ? '1' : '0';
            $branch_details['status']=$bl->status;
            VendorDetails::create($branch_details);
        }
    }

    public function check_vendor_name($vendor_name){
        $check_vendor=VendorHead::where('vendor_name',$vendor_name)->get();
        $count_vendor=$check_vendor->count();
        // return response()->json($count_vendor);
        return response()->json([
            'count_vendor'=>$count_vendor
        ],200);
    }

    public function edit_vendor($id){
        $vendor_head = VendorHead::find($id);
        $vendor_details = VendorDetails::where('vendor_head_id',$id)->orderby('address', 'ASC')->get();
        return response()->json([
            'vendor_head'=>$vendor_head,
            'vendor_details'=>$vendor_details
        ],200);
    }

    public function edit_branch($id){
        $branch_details = VendorDetails::find($id);
        return response()->json([
            'branch_details'=>$branch_details
        ],200);
    }

    public function update_branch(Request $request, $id){
        $branch_dets=VendorDetails::where('id',$id)->first();
        $branch_dets_update['phone']=$request->input('phone');
        $branch_dets_update['fax']=$request->input('fax');
        $branch_dets_update['contact_person']=$request->input('contact_person');
        $branch_dets_update['email']=$request->input('email');
        $branch_dets_update['notes']=$request->input('notes');
        $branch_dets_update['status']=$request->input('status');
        $branch_dets->update($branch_dets_update);
    }

    public function update_vendor(Request $request, $id){
        $head=VendorHead::where('id',$id)->first();
        $head_dets['vendor_name']=$request->input('vendor_name');
        $head->update($head_dets);

        $branch_list = $request->input('vendor_branches');
            foreach(json_decode($branch_list) as $bl){
                if($bl->id == 0){
                    $branch_details['vendor_head_id']=$request->input('head_id');
                    $branch_details['address']=$bl->address;
                    $branch_details['identifier']=$bl->identifier;
                    $branch_details['terms']=$bl->terms;
                    $branch_details['phone']=$bl->phone;
                    $branch_details['fax']=$bl->fax;
                    $branch_details['contact_person']=$bl->contact_person;
                    $branch_details['email']=$bl->email;
                    $branch_details['tin']=$bl->tin;
                    $branch_details['type']=$bl->type;
                    $branch_details['notes']=$bl->notes;
                    $branch_details['ewt']=$bl->ewt;
                    $branch_details['vat']=($bl->vat == 'Vat') ? '1' : '0';
                    $branch_details['status']=$bl->status;
                    VendorDetails::create($branch_details);
                }
            }
    }

}
