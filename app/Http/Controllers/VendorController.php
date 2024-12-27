<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorHead;
use App\Models\VendorDetails;
use App\Models\VendorTerms;

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
            // $branch_details['terms']=$bl->terms;
            $branch_details['phone']=$bl->phone;
            $branch_details['fax']=$bl->fax;
            $branch_details['contact_person']=$bl->contact_person;
            $branch_details['email']=$bl->email;
            $branch_details['tin']=$bl->tin;
            $branch_details['type']=$bl->type;
            $branch_details['notes']=$bl->notes;
            $branch_details['ewt']=$bl->ewt;
            $branch_details['vat']=$bl->vat;
            $branch_details['status']=$bl->status;
            // VendorDetails::create($branch_details);
            $vendor_details_id=VendorDetails::insertGetId($branch_details);

            foreach($bl->terms as $tl){
                $branch_terms['vendor_head_id']=$vendor_head_id;
                $branch_terms['vendor_details_id']=$vendor_details_id;
                $branch_terms['order_no']=$tl->order_no;
                $branch_terms['terms']=$tl->term_desc;
                VendorTerms::create($branch_terms);
            }
        }

        return $vendor_head_id;
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
        $detail = VendorDetails::where('vendor_head_id',$id)->orderby('address', 'ASC')->get();
        $count_branches = $detail->count();
        $count_wt = VendorTerms::where('vendor_head_id',$id)->get()->unique('vendor_details_id');
        $count_with_terms = $count_wt->count();
        foreach($detail AS $det){
            $branch_terms = VendorTerms::where('vendor_details_id',$det->id)->orderby('order_no', 'ASC')->get();
            $vendor_details[]= [
                'id'=>$det->id,
                'venoder_head_id'=>$det->vender_head_id,
                'address'=>$det->address,
                'identifier'=>$det->identifier,
                'phone'=>$det->phone,
                'fax'=>$det->fax,
                'contact_person'=>$det->contact_person,
                'email'=>$det->email,
                'tin'=>$det->tin,
                'type'=>$det->type,
                'ewt'=>$det->ewt,
                'vat'=>$det->vat,
                'notes'=>$det->notes,
                'status'=>$det->status,
                'terms'=> $branch_terms
            ];
        }
        return response()->json([
            'vendor_head'=>$vendor_head,
            'vendor_details'=>$vendor_details,
            'count_branches'=>$count_branches,
            'count_with_terms'=>$count_with_terms
        ],200);
    }

    public function edit_branch($id){
        $branch_details = VendorDetails::find($id);
        $branch_terms = VendorTerms::where('vendor_details_id',$id)->orderby('order_no', 'ASC')->get();
        return response()->json([
            'branch_details'=>$branch_details,
            'branch_terms'=>$branch_terms
        ],200);
    }

    public function update_branch(Request $request, $vendor_details_id){
        $branch_dets=VendorDetails::where('id',$vendor_details_id)->first();
        $branch_dets_update['phone']=$request->input('phone');
        $branch_dets_update['fax']=$request->input('fax');
        $branch_dets_update['contact_person']=$request->input('contact_person');
        $branch_dets_update['email']=$request->input('email');
        $branch_dets_update['notes']=$request->input('notes');
        $branch_dets_update['ewt']=$request->input('ewt');
        $branch_dets_update['status']=$request->input('status');
        $branch_dets->update($branch_dets_update);

        $update_terms_list = $request->input('terms');
        foreach(json_decode($update_terms_list) as $ut){
            if($ut->id != 0){
                $update_terms=VendorTerms::where('id',$ut->id)->first();
                $branch_terms['order_no']=$ut->order_no;
                $branch_terms['terms']=$ut->terms;
                $update_terms->update($branch_terms);
            }else{
                $branch_terms['vendor_head_id']=$request->input('vendor_head_id');
                $branch_terms['vendor_details_id']=$vendor_details_id;
                $branch_terms['order_no']=$ut->order_no;
                $branch_terms['terms']=$ut->terms;
                VendorTerms::create($branch_terms);
            }
        }
    }

    public function update_vendor(Request $request, $vendor_head_id){
        $head=VendorHead::where('id',$vendor_head_id)->first();
        $head_dets['vendor_name']=$request->input('vendor_name');
        $head_dets['product_services']=$request->input('product_services');
        $head->update($head_dets);

        $branch_list = $request->input('vendor_branches');
            foreach(json_decode($branch_list) as $bl){
                if($bl->id == 0){
                    $branch_details['vendor_head_id']=$request->input('head_id');
                    $branch_details['address']=$bl->address;
                    $branch_details['identifier']=$bl->identifier;
                    $branch_details['phone']=$bl->phone;
                    $branch_details['fax']=$bl->fax;
                    $branch_details['contact_person']=$bl->contact_person;
                    $branch_details['email']=$bl->email;
                    $branch_details['tin']=$bl->tin;
                    $branch_details['type']=$bl->type;
                    $branch_details['notes']=$bl->notes;
                    $branch_details['ewt']=$bl->ewt;
                    $branch_details['vat']=$bl->vat;
                    $branch_details['status']=$bl->status;
                    // VendorDetails::create($branch_details);
                    $vendor_details_id=VendorDetails::insertGetId($branch_details);

                    foreach($bl->terms as $tl){
                        $branch_terms['vendor_head_id']=$vendor_head_id;
                        $branch_terms['vendor_details_id']=$vendor_details_id;
                        $branch_terms['order_no']=$tl->order_no;
                        $branch_terms['terms']=$tl->terms;
                        VendorTerms::create($branch_terms);
                    }
                }
            }
    }

    public function no_terms_branch($vendor_head_id){
        $all_branches = VendorDetails::where('vendor_head_id',$vendor_head_id)->get();
        $no_terms=array();
        foreach($all_branches AS $ab){
            $with_terms = VendorTerms::where('vendor_head_id',$vendor_head_id)->where('vendor_details_id',$ab->id)->count();
            if($with_terms == 0){
                $no_terms[]= [
                    'checkbox'=>0,
                    'id'=>$ab->id,
                    'vendor_head_id'=>$ab->vendor_head_id,
                    'address'=>$ab->address,
                    'identifier'=>$ab->identifier,
                    'phone'=>$ab->phone,
                    'fax'=>$ab->fax,
                    'contact_person'=>$ab->contact_person,
                    'email'=>$ab->email,
                    'tin'=>$ab->tin,
                    'type'=>$ab->type,
                    'ewt'=>$ab->ewt,
                    'vat'=>$ab->vat,
                    'notes'=>$ab->notes,
                    'status'=>$ab->status,
                ];
            }
        }
        return response()->json([
            'no_terms_branch'=>$no_terms,
        ],200);
    }

    public function add_all_terms(Request $request){
        $noterms_branch = $request->input('no_terms_branch');
        $allterm_list = $request->input('all_term_list');
        
        foreach(json_decode($noterms_branch) as $nt){
            if($nt->checkbox != 0){
                foreach(json_decode($allterm_list) as $at){
                    $branch_terms['vendor_head_id']=$request->input('vendor_head_id');
                    $branch_terms['vendor_details_id']=$nt->id;
                    $branch_terms['order_no']=$at->all_order_no;
                    $branch_terms['terms']=$at->all_term_desc;
                    VendorTerms::create($branch_terms);
                }
            }
        }
    }

}
