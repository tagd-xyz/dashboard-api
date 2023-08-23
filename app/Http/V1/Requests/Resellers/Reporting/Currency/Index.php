<?php

namespace App\Http\V1\Requests\Resellers\Reporting\Currency;

use Illuminate\Foundation\Http\FormRequest;

class Index extends FormRequest
{
    public const DATE_FROM = 'dateFrom';

    public const DATE_TO = 'dateTo';

    public const FILTER = 'filter';

    public const FILTER_BRANDS = 'filter.brands';

    public const FILTER_MODEL = 'filter.model';

    public const FILTER_COUNTRIES = 'filter.countries';

    public const FILTER_CITY = 'filter.city';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            self::DATE_FROM => 'string|date',
            self::DATE_TO => 'string|date',
            self::FILTER => 'json',
            self::FILTER_BRANDS => 'nullable|array|min:1',
            self::FILTER_MODEL => 'nullable|string',
            self::FILTER_COUNTRIES => 'nullable|array|min:1',
            self::FILTER_CITY => 'nullable|string',
        ];
    }
}
