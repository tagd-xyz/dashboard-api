<?php

namespace App\Http\V1\Requests\Resellers\Reporting\Currency;

class Graph extends Index
{
    public const CURRENCY = 'currency';

    public const DAYS_CHUNK = 'daysChunk';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            ...parent::rules(),
            self::CURRENCY => 'string|required',
            self::DAYS_CHUNK => 'numeric|min:1',
        ];
    }
}
