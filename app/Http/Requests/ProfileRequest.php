<?php

namespace App\Http\Requests;

use App\Rules\Base64ImageValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:5|max:20',
            'email' => 'required|email|max:20',
            'contact' => 'required|numeric|digits:10',
            'password' => 'nullable|string|min:6|max:10',
            'profile_pic' => ['nullable', new Base64ImageValidationRule()],
        ];
    }
}
