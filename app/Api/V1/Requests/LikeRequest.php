<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class LikeRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.Like.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
