<?php

namespace App\Http\V1\Controllers\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
// use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Tagd as TagdModel;

trait Currency
{
    protected $actingAs = null;

    protected $filterBrands = null;

    protected $filterModel = null;

    protected $filterCountries = null;

    protected $filterCity = null;

    protected $filterDateFrom = null;

    protected $filterDateTo = null;

    /**
     * Determine if the current user is acting as a retailer.
     */
    protected function isActingAsRetailer(): bool
    {
        return $this->actingAs instanceof Retailer;
    }

    /**
     * Determine if the current user is acting as a reseller.
     */
    protected function isActingAsReseller(): bool
    {
        return $this->actingAs instanceof Reseller;
    }

    /**
     * Get the tagds of interest
     */
    private function filteredTagds(string $currency = null): EloquentBuilder
    {
        return
            TagdModel::when(! is_null($currency), function (EloquentBuilder $query) use ($currency) {
                $query->where('meta->price->currency', $currency);
            })
                ->when(! is_null($this->filterDateFrom), function (EloquentBuilder $query) {
                    $query->where('created_at', '>=', $this->filterDateFrom);
                })
                ->when(! is_null($this->filterDateTo), function (EloquentBuilder $query) {
                    $query->where('created_at', '<=', $this->filterDateTo);
                })
                ->when($this->isActingAsRetailer(), function (EloquentBuilder $query) {
                    $query->whereHas('item', function (EloquentBuilder $query) {
                        $query->where('retailer_id', $this->actingAs->id);
                    });
                })
                ->when($this->isActingAsReseller(), function (EloquentBuilder $query) {
                    $query->where('reseller_id', $this->actingAs->id);
                })
                ->when(! is_null($this->filterModel), function (EloquentBuilder $query) {
                    $query->whereHas('item', function ($query) {
                        $query->where('properties->model', 'LIKE', '%' . $this->filterModel . '%');
                    });
                })
                ->when(! is_null($this->filterBrands), function (EloquentBuilder $query) {
                    $query->whereHas('item', function ($query) {
                        $query->whereIn('properties->brand', $this->filterBrands);
                    });
                })
                ->when(! is_null($this->filterCountries), function (EloquentBuilder $query) {
                    $query->whereIn('meta->location->country', $this->filterCountries);
                })
                ->when(! is_null($this->filterCity), function (EloquentBuilder $query) {
                    $query->where('meta->location->city', 'LIKE', '%' . $this->filterCity . '%');
                });
    }

    /**
     * Get the distinct currencies of the tagds of interest
     */
    private function distinctCurrencies(): Collection
    {
        return $this
            ->filteredTagds()
            ->selectRaw("json_unquote(json_extract(`meta`, '$.price.currency')) as currency")
            ->whereNotNull('meta->price->currency')
            ->distinct()
            ->get()
            ->pluck('currency');
    }

    /**
     * Get the minimum price of the tagds of interest
     */
    private function min(string $currency): float
    {
        return $this
            ->filteredTagds($currency)
            ->selectRaw("min(json_extract(`meta`, '$.price.amount')) as min")
            ->get()
            ->pluck('min')
            ->first() ?? 0.0;
    }

    /**
     * Get the maximum price of the tagds of interest
     */
    private function max(string $currency): float
    {
        return $this
            ->filteredTagds($currency)
            ->selectRaw("max(json_extract(`meta`, '$.price.amount')) as max")
            ->get()
            ->pluck('max')
            ->first() ?? 0.0;
    }

    /**
     * Get the average mean price of the tagds of interest
     */
    private function avgMean(string $currency): float
    {
        return round(
            $this
                ->filteredTagds($currency)
                ->selectRaw("avg(json_extract(`meta`, '$.price.amount')) as avgMean")
                ->get()
                ->pluck('avgMean')
                ->first() ?? 0.0,
            2
        );
    }

    /**
     * Get the average median price of the tagds of interest
     */
    private function avgMedian(string $currency): float
    {
        // https://stackoverflow.com/questions/1291152/simple-way-to-calculate-median-with-mysql

        $tagds = TagdModel::where('meta->price->currency', $currency)
            ->when(! is_null($this->filterBrands), function (EloquentBuilder $query) {
                $query->whereHas('item', function ($query) {
                    $query->whereIn('properties->brand', $this->filterBrands);
                });
            })
            ->whereHas('item', function (EloquentBuilder $query) {
                $query->where('retailer_id', $this->actingAs->id);
            });

        DB::statement(DB::raw('set @rownum=0'));
        $raw = $tagds
            ->selectRaw("json_extract(`meta`, '$.price.amount') as amount, tagds.deleted_at, @rownum:=@rownum+1 as `row_number`, @total_rows:=@rownum");

        return TagdModel::selectRaw('avg(tagds.amount) as avg_median')
            ->fromSub($raw, 'tagds')
            ->whereRaw('tagds.row_number IN ( FLOOR((@total_rows+1)/2), FLOOR((@total_rows+2)/2) )')
            ->get()
            ->pluck('avg_median')
            ->first() ?? 0.0;
    }

    /**
     * Get the standard deviation price of the tagds of interest
     */
    private function stdDev(string $currency): float
    {
        return round(floatval($this
            ->filteredTagds($currency)
            ->selectRaw("stddev(json_extract(`meta`, '$.price.amount')) as stdDev")
            ->get()
            ->pluck('stdDev')
            ->first() ?? 0.0), 2);
    }

    /**
     * Get the quantile price of the tagds of interest
     *
     * @throws InvalidArgumentException
     */
    private function quantile(string $currency, int $number, int $count = 4): array
    {
        // TODO: Upgrade to mysql 8.0 and use ntile() function

        $list = $this
            ->filteredTagds($currency)
            ->selectRaw("json_extract(`meta`, '$.price.amount') as amount")
            ->orderBy('meta->price->amount', 'asc')
            ->get()
            ->pluck('amount')
            ->toArray();

        $quantile = (1 / $count * $number);
        // $quantile = min(100, max(0, $quantile));

        $array = array_values($list);

        if (empty($array)) {
            return [
                'value' => 0.0,
                'items' => 0,
            ];
        }

        // sort($array);
        $index = ($quantile / 100) * (count($list) - 1);
        $fractionPart = $index - floor($index);
        $intPart = floor($index);

        $percentile = $list[$intPart];
        $percentile += ($fractionPart > 0) ? $fractionPart * ($list[$intPart + 1] - $array[$intPart]) : 0;

        if ($number > 1) {
            $percentilePrev = $this->quantile($currency, $number - 1, $count)['value'];
        } else {
            $percentilePrev = 0;
        }

        return [
            'value' => round($percentile, 2),
            'items' => $this->filteredTagds($currency)
                ->where('meta->price->amount', '>=', $percentilePrev)
                ->where('meta->price->amount', '<=', $percentile)
                ->count(),
        ];

        // $pos = intval(floor((count($list) - 1) * $quantile));

        // if (3 == $number) {
        //     dd($pos);
        // } else {
        //     // dd('b');
        // }

        // return $list[$pos];
    }

    /**
     * Get the total number of items transferred of the tagds of interest
     */
    private function itemsTransferred(string $currency): int
    {
        return intval($this
            ->filteredTagds($currency)
            ->sum('stats->count->transferred_consumer'));
    }

    /**
     * Get the total number of items of interest
     */
    private function itemsAffected(string $currency): int
    {
        return $this
            ->filteredTagds($currency)
            ->count();
    }
}
