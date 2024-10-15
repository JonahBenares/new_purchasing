<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThingsTodo;
use App\Models\Reminders;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function create_todo(Request $request){
        $formData=[
            'todo_description'=>'',
        ];
        return response()->json($formData);
    }

    public function create_reminder(Request $request){
        $formData=[
            'assigned_to'=>0,
            'reminder_due_date'=>'',
            'reminder_desc'=>'',
        ];
        return response()->json($formData);
    }

    public function add_todo(Request $request){
        $validated=$this->validate($request,[
                'todo_description'=>['required','string'],
            ],
            [
                'todo_description.required'=> 'The description field is required.',
            ]
        );
        $validated['user_id']=Auth::id();
        ThingsTodo::create($validated);
    }

    public function add_reminder(Request $request){
        $validated=$this->validate($request,[
                'reminder_due_date'=>['required','string'],
                'reminder_desc'=>['required','string'],
            ],
            [
                'reminder_due_date.required'=> 'The due date field is required.',
                'reminder_desc.required'=> 'The description field is required.',
            ]
        );
        $employee_name=User::where('id',$request->assigned_to)->value('name');
        $validated['employee_name']=$employee_name;
        $validated['assigned_to']=$request->assigned_to;
        $validated['user_id']=Auth::id();
        Reminders::create($validated);
    }

    public function get_todo(){
        $todo = ThingsTodo::where('user_id',Auth::id())->where('status','0')->orderByDesc('created_at')->get();
        return response()->json([
            'todo'=>$todo,
        ],200);
    }

    public function get_reminder(){
        $reminder = Reminders::where('status','0')->where(function ($q) {
            $q->where('user_id', Auth::id())->orWhere('assigned_to', Auth::id());
        })->orderBy('reminder_due_date')->get();
        return response()->json([
            'reminder'=>$reminder,
        ],200);
    }

    public function complete_todo($id){
        $todo=ThingsTodo::where('id',$id)->first();
        $validated['status']='1';
        $validated['date_finished']=date('Y-m-d H:i:s');
        $todo->update($validated);
    }

    public function complete_reminder($id){
        $reminders=Reminders::where('id',$id)->first();
        $validated['status']='1';
        $validated['date_finished']=date('Y-m-d H:i:s');
        $reminders->update($validated);
    }

    public function complete_all_todo(Request $request){
        $todo=$request->input("todo");
        foreach(json_decode($todo) AS $t){
            $todo=ThingsTodo::where('id',$t)->first();
            $validated['status']='1';
            $validated['date_finished']=date('Y-m-d H:i:s');
            $todo->update($validated);
        }
    }

    public function complete_all_reminder(Request $request){
        $reminder=$request->input("reminder");
        foreach(json_decode($reminder) AS $r){
            $reminders=Reminders::where('id',$r)->first();
            $validated['status']='1';
            $validated['date_finished']=date('Y-m-d H:i:s');
            $reminders->update($validated);
        }
    }
}
