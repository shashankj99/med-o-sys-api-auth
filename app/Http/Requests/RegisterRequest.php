<?php

namespace App\Http\Requests;

class RegisterRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'province_id' => 'required',
            'district_id' => 'required',
            'city_id' => 'required',
            'ward_no' => 'required|numeric|min:1|max:30',
            'dob_ad' => 'required|date',
            'dob_bs' => 'required|date',
            'mobile' => 'required|numeric|digits:10|unique:users',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|string|confirmed',
            'age' => 'required|numeric|min:1|max:125',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'gender' => 'required|in:male,female,others'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name cannot be blank',
            'last_name.required' => 'Last name cannot be blank',
            'province_id.required' => 'Province must be selected',
            'district_id.required' => 'District must be selected',
            'city_id.required' => 'City must be selected',
            'ward_no.required' => 'Ward no must be selected',
            'ward_no.numeric' => 'Ward no must only be a number',
            'ward_no.min' => 'You can\'t select less than 1 for the ward number',
            'ward_no.max' => 'You can\'t select more than 30 for the ward number',
            'dob_ad.required' => 'Date of birth(A.D.) is required',
            'dob_ad.date' => 'The birth date must be of valid date format',
            'dob_bs.required' => 'Date of birth(B.S.) is required',
            'dob_bs.date' => 'The birth date must be of valid date format',
            'mobile.required' => 'The mobile number is required',
            'mobile.numeric' => 'The mobile number must be a numeric value',
            'mobile.digits' => 'The mobile number must be of 10 digits',
            'mobile.unique' => 'The mobile number has already been taken',
            'email.required' => 'The email is required',
            'email.email' => 'The email must be a valid email address',
            'email.unique' => 'The email has already been taken',
            'password.required' => 'Password field is required',
            'password.confirmed' => 'Password confirmation didn\'t match',
            'age.required' => 'Age is required',
            'age.numeric' => 'Age must be a number',
            'age.min' => 'Age can not be less than 1',
            'age.max' => 'Age can not be more than 125',
            'blood_group.required' => 'Blood group is required',
            'blood_group.in' => 'Blood group must be of valid type',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Gender can only either male or female or others'
        ];
    }
}
