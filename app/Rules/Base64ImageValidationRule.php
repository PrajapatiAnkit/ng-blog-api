<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64ImageValidationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $fileParts = explode(';base64,',$value);
        $fileTypeParts = explode('/',$fileParts[0] );
        $fileExtension = strtolower($fileTypeParts[1]);
        if (in_array($fileExtension, ['png','jpg','jpeg'])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Profile pic should be image. Allowed file are png and jpeg';
    }
}
