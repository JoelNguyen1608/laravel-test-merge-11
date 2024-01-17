<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:stylists,email',
            'password' => 'required',
            'keep_session_active' => 'sometimes|boolean'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.exists' => 'Email does not exist.',
            'password.required' => 'Password is required.',
            'keep_session_active.boolean' => 'Keep session active must be a boolean.'
        ];
    }
}
