<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Resellers\Reporting\Currency;

use Illuminate\Support\Carbon;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Item\Tagd;
use Tests\Feature\V1\Resellers\Reporting\Base;

class DateGetTest extends Base
{
    private function createTagd(Reseller $reseller, Carbon $date, float $amount): Tagd
    {
        Carbon::setTestNow($date);

        $consumer = $this->aConsumer();

        $tagd = $this->aTagd([
            // 'consumer' => $consumer,
            'reseller' => $reseller,
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
     * GET /resellers/reporting/currency
     *
     * @return void
     */
    // public function test_res_rep_cur_date_get_request()
    // {
    //     $reseller = $this->aReseller();

    //     $this->createTagd($reseller, new Carbon('2020-01-15'), 10.50);
    //     $this->createTagd($reseller, new Carbon('2020-02-15'), 20.50);
    //     $this->createTagd($reseller, new Carbon('2020-03-15'), 30.50);
    //     $this->createTagd($reseller, new Carbon('2020-04-15'), 40.50);
    //     $this->createTagd($reseller, new Carbon('2020-05-15'), 50.50);
    //     $this->createTagd($reseller, new Carbon('2020-06-15'), 60.50);

    //     $url = static::URL_RES_REP_CURRENCY . '?filter={"dateFrom":"2020-03-01","dateTo":"2020-05-01"}';

    //     $response = $this
    //         ->actingAsAReseller($reseller)
    //         ->get($url)
    //         ->assertStatus(200)
    //         ->assertJsonPath('data.GBP.min', 30.5)
    //         ->assertJsonPath('data.GBP.max', 40.5);
    // }
}
