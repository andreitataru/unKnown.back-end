<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class GetRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.Get.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
