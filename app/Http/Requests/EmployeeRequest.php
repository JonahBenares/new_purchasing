<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'access'=>'integer|nullable',
            'name'=>'required|unique:users,name,'.$this->id,
            'email'=> 'required_if:access,1,email|nullable|unique:users,email,'.$this->id,
            'password'=>'required_if:access,1|nullable|min:6|max:10,password,'.$this->id,
            'temp_password'=>'nullable|min:6|max:10',
            'user_type'=>'required_if:access,1,user_type|nullable'.$this->id,
            'department_id'=>'integer',
            'position'=>'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required_if' => 'Email Address field is required',
            'user_type.required_if' => 'Type field is required',
        ];
    }
}
