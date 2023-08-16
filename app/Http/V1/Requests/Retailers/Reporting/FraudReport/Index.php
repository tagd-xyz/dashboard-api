<?php

namespace App\Http\V1\Requests\Retailers\Reporting\FraudReport;

use Illuminate\Foundation\Http\FormRequest;

class Index extends FormRequest
{
    public const PER_PAGE = 'perPage';

    public const PAGE = 'page';

    public const DIRECTION = 'direction';

    public const DATE_FROM = 'dateFrom';

    public const DATE_TO = 'dateTo';

    public const FILTER = 'filter';

    public const FILTER_BRANDS = 'filter.brands';

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
            self::PER_PAGE => 'numeric|min:1|max:9999',
            self::PAGE => 'numeric|min:1|max:9999',
            self::DIRECTION => 'string|in:asc,desc',
            self::FILTER => 'json',
            self::FILTER_BRANDS => 'nullable|array|min:1',
        ];
    }
}
