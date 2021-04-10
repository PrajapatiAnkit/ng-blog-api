<?php

namespace App\Http\Requests;

use App\Rules\Base64ImageValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title' => 'required|string|min:5|max:255',
            'content' => 'required|string|min:5|max:255',
            'tags' => 'required|string|min:5|max:255',
            'feature_image' => ['nullable', new Base64ImageValidationRule()],
        ];
    }
}
