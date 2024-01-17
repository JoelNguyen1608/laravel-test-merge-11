
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'stylist_id' => 'required|exists:stylists,id',
            'token' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|same:new_password',
        ];
    }
}
