<?php

namespace App\Http\V1\Controllers\Retailers\Reporting;

use App\Http\V1\Controllers\Controller as Controller;
use App\Http\V1\Controllers\Traits\Currency as CurrencyTrait;
use App\Http\V1\Requests\Retailers\Reporting\Currency\Graph as GraphRequest;
use App\Http\V1\Requests\Retailers\Reporting\Currency\Index as IndexRequest;
use Illuminate\Support\Carbon;

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
                'itemsTransferred' => $this->itemsTransferred($currency),
                'itemsAffected' => $this->itemsAffected($currency),
            ];
        }

        return response()->withData(
            $data
        );
    }

    protected function graph(GraphRequest $request)
    {
        $this->actingAs = $this->actingAs($request);

        $filter = json_decode($request->get(GraphRequest::FILTER, '{}'), true);
        $this->filterBrands = $filter['brands'] ?? null;
        $this->filterModel = $filter['model'] ?? null;
        $this->filterCountries = $filter['countries'] ?? null;
        $this->filterCity = $filter['city'] ?? null;

        $currency = $request->get(GraphRequest::CURRENCY, 'GBP');
        $daysChunk = $request->get(GraphRequest::DAYS_CHUNK, 3);

        $filterDateFrom = $request->get(GraphRequest::DATE_FROM, null);
        $filterDateTo = $request->get(GraphRequest::DATE_TO, null);

        $filterDateFrom = is_null($filterDateFrom)
            ? Carbon::now()->subDays(30)
            : Carbon::parse($filterDateFrom);

        $filterDateTo = is_null($filterDateTo)
            ? Carbon::now()
            : Carbon::parse($filterDateTo);

        $dateIterator = $filterDateFrom->clone()->startOfDay();

        $data = [];
        while ($dateIterator < $filterDateTo) {
            $this->filterDateFrom = $dateIterator->clone();
            $this->filterDateTo = $dateIterator->addDays($daysChunk);

            $key = $this->filterDateFrom->format('Y-m-d');

            $data[$key] = [
                'min' => $this->min($currency),
                'max' => $this->max($currency),
                'mean' => $this->avgMean($currency),
                'median' => $this->avgMedian($currency),
                'stdDev' => $this->stdDev($currency),
                // 'quantiles' => [
                //     'q1' => $this->quantile($currency, 1),
                //     'q2' => $this->quantile($currency, 2),
                //     'q3' => $this->quantile($currency, 3),
                //     'q4' => $this->quantile($currency, 4),
                // ],
                // 'itemsTransferred' => $this->itemsTransferred($currency),
                // 'itemsAffected' => $this->itemsAffected($currency),
            ];
        }

        return response()->withData(
            $data
        );
    }
}
