<?php

namespace App\Http\V1\Controllers\Admins\Reporting;

use App\Http\V1\Controllers\Controller as Controller;
use App\Http\V1\Controllers\Traits\Currency as CurrencyTrait;
use App\Http\V1\Requests\Admins\Reporting\Currency\Index as IndexRequest;

class Currency extends Controller
{
    use CurrencyTrait;

    protected function index(IndexRequest $request)
    {
        $this->actingAs = $this->actingAs($request);

        $this->filterDateFrom = $request->get(IndexRequest::DATE_FROM, null);
        $this->filterDateTo = $request->get(IndexRequest::DATE_TO, null);

        $filter = json_decode($request->get(IndexRequest::FILTER, '{}'), true);
        $this->filterBrands = $filter['brands'] ?? null;
        $this->filterModel = $filter['model'] ?? null;
        $this->filterCountries = $filter['countries'] ?? null;
        $this->filterCity = $filter['city'] ?? null;
        $this->filterRetailers = $filter['retailers'] ?? null;
        $this->filterResellers = $filter['resellers'] ?? null;

        // fetch all currencies
        $currencies = $this->distinctCurrencies();

        $data = [];
        foreach ($currencies as $currency) {

            $data[$currency] = [
                'min' => $this->min($currency),
                'max' => $this->max($currency),
                'mean' => $this->avgMean($currency),
                'median' => $this->avgMedian($currency),
                'stdDev' => $this->stdDev($currency),
                'quantiles' => [
                    'q1' => $this->quantile($currency, 1),
                    'q2' => $this->quantile($currency, 2),
                    'q3' => $this->quantile($currency, 3),
                    'q4' => $this->quantile($currency, 4),
                ],
                'items' => $this->itemsTransferred($currency),
            ];
        }

        return response()->withData(
            $data
        );
    }
}
