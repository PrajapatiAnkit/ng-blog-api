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
            'categories.*' => 'required',
            'tags' => 'required|string|min:5|max:255',
            'featured_image' => 'nullable|mimes:jpg,jpeg,png,JPG,JPEG,PNG',
            'content' => 'required|string|min:5|max:500',
            //'featured_image' => ['nullable', new Base64ImageValidationRule()],
        ];
    }
}
