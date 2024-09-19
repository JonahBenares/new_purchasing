<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\Departments;

class EmployeeController extends Controller
{
    public function create_employee(Request $request){
        $formData=[
            'employee_name'=>'',
            'department_id'=>'',
            'position'=>'',
        ];
        return response()->json($formData);
    }

    public function get_all_employee(Request $request){
        $employee_all = Employees::orderBy('employee_name','ASC')->get();
        $employeearray=array();
        foreach($employee_all AS $e){
            $department_name=Departments::where('id',$e->department_id)->value('department_name');
            $employeearray[]=[
                'id'=>$e->id,
                $e->employee_name,
                $department_name,
                $e->position,
                ''
            ];
        }
        return response()->json([
            'employee'=>$employeearray,
        ],200);
    }

    public function add_employee(Request $request){
        $validated=$this->validate($request,[
            'employee_name'=>['required','string'],
            'department_id'=>['integer','nullable'],
            'position'=>['string','nullable'],
        ]);
        Employees::create($validated);
    }

    public function edit_employee($id){
        $employee = Employees::where('id',$id)->first();
        return response()->json([
            'employee'=>$employee
        ],200);
    }

    public function update_employee(Request $request, $id){
        $employees=Employees::where('id',$id)->first();
        $validated=$this->validate($request,[
            'employee_name'=>'required|string',
            'department_id'=>'integer',
            'position'=>'string|nullable',
        ]);
        $employees->update($validated);
    }

    public function get_department(){
        $department = Departments::orderBy('department_name','ASC')->get();
        return response()->json([
            'department'=>$department
        ],200);
    }
}
