<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Retailers\Reporting\Currency;

use Illuminate\Support\Carbon;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Tagd;
use Tests\Feature\V1\Retailers\Reporting\Base;

class DateGetTest extends Base
{
    private function createTagd(Retailer $retailer, Carbon $date, float $amount): Tagd
    {
        Carbon::setTestNow($date);

        $consumer = $this->aConsumer();

        $tagd = $this->aTagd([
            'consumer' => $consumer,
            'retailer' => $retailer,
        ]);

        $tagd->update([
            'meta' => [
                'price' => [
                    ...$tagd->meta['price'],
                    'amount' => $amount,
                ],
            ],
        ]);

        $tagd->activate();

        return $tagd;
    }

    /**
     * GET /retailers/reporting/currency
     *
     * @return void
     */
    public function test_ret_rep_cur_date_get_request()
    {
        $retailer = $this->aRetailer();

        $this->createTagd($retailer, new Carbon('2020-01-15'), 10.50);
        $this->createTagd($retailer, new Carbon('2020-02-15'), 20.50);
        $this->createTagd($retailer, new Carbon('2020-03-15'), 30.50);
        $this->createTagd($retailer, new Carbon('2020-04-15'), 40.50);
        $this->createTagd($retailer, new Carbon('2020-05-15'), 50.50);
        $this->createTagd($retailer, new Carbon('2020-06-15'), 60.50);

        $url = static::URL_RET_REP_CURRENCY . '?filter={"dateFrom":"2020-03-01","dateTo":"2020-05-01"}';

        $response = $this
            ->actingAsARetailer($retailer)
            ->get($url)
            ->assertStatus(200)
            ->assertJsonPath('data.GBP.min', 30.5)
            ->assertJsonPath('data.GBP.max', 40.5);
    }
}
