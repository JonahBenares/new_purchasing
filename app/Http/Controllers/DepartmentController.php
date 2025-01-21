<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departments;
use Illuminate\Support\Facades\DB;
use Throwable;
class DepartmentController extends Controller
{
    public function create_department(Request $request){
        $formData=[
            'department_name'=>'',
            'department_code'=>'',
        ];
        return response()->json($formData);
    }

    public function get_all_department(Request $request){
        $department_all = Departments::orderBy('department_name','ASC')->get();
        $departmentarray=array();
        foreach($department_all AS $d){
            $departmentarray[]=[
                'id'=>$d->id,
                $d->department_code,
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
            'department_name'=>['unique:department','required','string'],
            'department_code'=>['required','string'],
        ]);
        try {
            DB::beginTransaction();
            DB::commit();
            Departments::create($validated);
        } catch (Throwable $e) {
            DB::rollBack();
            echo 'error';
        }
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
            'department_name'=>'required|string|unique:department,department_name,'.$id,
            'department_code'=>'required|string',
        ]);
        try {
            DB::beginTransaction();
            DB::commit();
            $departments->update($validated);
        } catch (Throwable $e) {
            DB::rollBack();
            echo 'error';
        }
    }
}
