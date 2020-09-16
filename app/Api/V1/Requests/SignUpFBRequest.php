<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class SignUpFBRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.sign_upFB.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
