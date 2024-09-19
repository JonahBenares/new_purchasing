<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departments;
class DepartmentController extends Controller
{
    public function create_department(Request $request){
        $formData=[
            'department_name'=>'',
        ];
        return response()->json($formData);
    }

    public function get_all_department(Request $request){
        $department_all = Departments::orderBy('department_name','ASC')->get();
        $departmentarray=array();
        foreach($department_all AS $d){
            $departmentarray[]=[
                'id'=>$d->id,
                $d->department_name,
                ''
            ];
        }
        return response()->json([
            'department'=>$departmentarray,
        ],200);
    }

    public function add_department(Request $request){
        $validated=$this->validate($request,[
            'department_name'=>['unique:department','required','string']
        ]);
        Departments::create($validated);
    }

    public function edit_department($id){
        $department = Departments::where('id',$id)->first();
        return response()->json([
            'department'=>$department
        ],200);
    }

    public function update_department(Request $request, $id){
        $departments=Departments::where('id',$id)->first();
        $validated=$this->validate($request,[
            'department_name'=>'required|string|unique:department,department_name,'.$id
        ]);
        $departments->update($validated);
    }
}