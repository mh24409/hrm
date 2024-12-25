<?php

namespace Modules\HumanResource\Http\Requests;

use App\Rules\UserExistRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'attendance_time_id' => 'required|integer',
            'employee_type_id' => 'required|integer',
            'date_of_birth' => 'required',
            'joining_date' => 'required',
            'hire_date' => 'required',
            'gender_id' => 'required',
            'sos' => 'required',
            'position_id' => 'required',
            'department_id' => 'required',
            'pay_frequency_id' => 'required',
            'email' => ['required', 'email', 'unique:employees', new UserExistRule('email')],
            'phone' => ['required', 'unique:employees', new UserExistRule('contact_no')],
            'gross_salary' => 'required|numeric',
            'basic_salary' => 'required|numeric',
            'profile_image' => 'image|mimes:jpg,png,webp',
            'employee_docs' => 'array',
            'is_supervisor' => 'required|in:1,0',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'gender_id.required' => 'Gender field is required',
            'position_id.required' => 'Designation field is required',
            'department_id.required' => 'Department field is required',
            'employee_group_id.required' => 'Employee Group field is required',
            'email.required' => 'Employee Email field is required',
            'phone.required' => 'Employee Phone field is required',
        ];
    }

}
