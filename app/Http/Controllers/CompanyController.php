<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{   
    public function get_all_company(Request $request){
        $all_company = Company::orderby('company_name', 'ASC')->get();
        $x=0;
        $companyarray=array();
        foreach($all_company AS $ac){
            $companyarray[]=[
                'id'=>$ac->id,
                $ac->company_name,
                ''
            ];
            $x++;
        }
        return response()->json([
            'companyarray'=>$companyarray,
        ],200);
    }

    public function create_company(Request $request){
        $formData=[
            'company_name'=>'',
        ];
        return response()->json($formData);
    }

    public function add_company(Request $request){

        $validated=$this->validate($request,[
            'company_name'=>['unique:company','required','string']
        ]);
        $company=Company::create($validated);
    }

    public function edit_company($id){
        $company = Company::find($id);
        return response()->json([
            'company'=>$company
        ],200);
    }

    public function update_company(Request $request, $id){
        $company=Company::where('id',$id)->first();
        $validated=$this->validate($request,[
            'company_name'=>'required|string|unique:company,company_name,'.$id
        ]);

        $company->update($validated);
    }
}
