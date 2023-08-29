<?php

namespace App\Http\V1\Requests\Resellers\Reporting\AvgResaleValue;

use Illuminate\Foundation\Http\FormRequest;

class Graph extends FormRequest
{
    public const MONTHS_AGO = 'monthsAgo';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            self::MONTHS_AGO => 'numeric|min:1',
        ];
    }
}
