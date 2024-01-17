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
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
            'keep_session' => 'sometimes|boolean'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.exists' => 'Email does not exist in our records.',
            'password.required' => 'Password is required.',
            'keep_session.boolean' => 'Keep session active must be a boolean.'
        ];
    }
}
