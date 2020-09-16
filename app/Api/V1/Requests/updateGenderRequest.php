<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class updateGenderRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.updateGender.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
