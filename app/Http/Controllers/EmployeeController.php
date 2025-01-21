<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\EmployeeEditRequest;
use App\Models\Employees;
use App\Models\Departments;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Throwable;
class EmployeeController extends Controller
{
    public function create_employee(Request $request){
        $randomString = Str::random(10);
        $formData=[
            'name'=>'',
            'department_id'=>0,
            'password'=>'',
            'position'=>'',
            'email'=>'',
            'access'=>0,
            'temp_password'=>$randomString,
            'user_type'=>'',
        ];
        return response()->json($formData);
    }

    public function get_all_employee(Request $request){
        $employee_all = User::orderBy('name','ASC')->get();
        $employeearray=array();
        foreach($employee_all AS $e){
            $department_name=Departments::where('id',$e->department_id)->value('department_name');
            $employeearray[]=[
                'id'=>$e->id,
                $e->name,
                $department_name,
                $e->position,
                ''
            ];
        }
        return response()->json([
            'employee'=>$employeearray,
        ],200);
    }

    public function add_employee(EmployeeRequest $request){
        // $validated=$this->validate($request,[
        //     'name'=>['required','string'],
        //     'department_id'=>['integer','nullable'],
        //     'position'=>['string','nullable'],
        //     'email'=> 'required_if:access,1,email|nullable|unique:users,email',
        //     'password'=>'required_if:access,1|nullable|min:6|max:10,password',
        //     'temp_password'=>'nullable|min:6|max:10',
        //     'user_type'=>['string','nullable'],
        //     'access'=>['integer','nullable'],
        // ]);
        $validated=$request->validated();
        try {
            DB::beginTransaction();
            DB::commit();
            User::create($validated);
        } catch (Throwable $e) {
            DB::rollBack();
            echo 'error';
        }
    }

    public function edit_employee($id){
        $employee = User::where('id',$id)->first();
        $randomString = Str::random(10);
        return response()->json([
            'employee'=>$employee,
            'random_string'=>$randomString,
        ],200);
    }

    public function update_employee(EmployeeEditRequest $request, $id){
        $employees=User::where('id',$id)->first();
        $validated = $request->validated();
        if($request->access=='1'){
            $validated = [
                'access'=>1,
                'name'=>$request->name,
                'email'=>$request->email,
                'position'=>$request->position,
                'user_type'=>$request->user_type,
                'department_id'=>$request->department_id,
                'password'=>$request->temp_password,
                'temp_password'=>$request->temp_password
            ];
        }else{
            $validated = [
                'access'=>0,
                'name'=>$request->name,
                'position'=>$request->position,
                'department_id'=>$request->department_id,
                'contact_no'=>$request->contact_no,
                'temp_password'=>'',
                'password'=>'',
                'user_type'=>'',
            ];
        }
        try {
            DB::beginTransaction();
            DB::commit();
            $employees->update($validated);
        } catch (Throwable $e) {
            DB::rollBack();
            echo 'error';
        }
    }

    public function get_department(){
        $department = Departments::orderBy('department_name','ASC')->get();
        return response()->json([
            'department'=>$department
        ],200);
    }
}
