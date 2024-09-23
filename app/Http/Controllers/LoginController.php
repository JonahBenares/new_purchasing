<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class LoginController extends Controller
{
    public function login_form(){
        $formData = [
            'email'=>null,
            'password'=> null,
        ];
        return response()->json($formData);
    }

   public function login_process(Request $request){
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if(Auth::attempt($credentials)) {
            $user = $request->user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            $success['user_type'] = $user->user_type;
            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User login successfully'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Unauthorized'
            ];
            return response()->json($response,200);     
        }
    }

    public function dashboard(){
        if(Auth::check()){
            $credentials=[
                'name' => Auth::user()?->name,
                'user_type' => Auth::user()?->user_type,
                'temp_password' => Auth::user()?->temp_password,
            ];
        }else{
            $credentials=[
                'name' => '',
                'user_type' => '',
                'temp_password' => '',
            ];
        }
        return response()->json($credentials);
   }

   public function change_password(){
        $credentials=[
            'id' => Auth::id(),
            'name' => Auth::user()->name,
        ];
        return response()->json($credentials);
    }

    public function create_credential(Request $request){
        $formData=[
            'new_password'=>'',
            'confirm_password'=>'',
        ];
        return response()->json($formData);
    }

    public function edit_password(Request $request){
        $employees=User::where('id',$request->user_id)->first();
        // $employees=User::where('id',$request->user_id)->where('change_password','=',0)->first();
        $validated=[
            'password' => $request->password,
            'temp_password'=>null,
            'change_password'=>1
        ];
        $employees->update($validated);
    }
}
