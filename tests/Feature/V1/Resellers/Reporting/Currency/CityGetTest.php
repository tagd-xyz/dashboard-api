<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Resellers\Reporting\Currency;

use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Item\Tagd;
use Tests\Feature\V1\Resellers\Reporting\Base;

class CityGetTest extends Base
{
    private function createTagd(Reseller $reseller, string $city, float $amount): Tagd
    {
        $consumer = $this->aConsumer();

        $tagd = $this->aTagd([
            // 'consumer' => $consumer,
            'reseller' => $reseller,
        ]);

        $tagd->update([
            'meta' => [
                ...$tagd->meta,
                'price' => [
                    ...$tagd->meta['price'],
                    'amount' => $amount,
                ],
                'location' => [
                    ...$tagd->meta['location'],
                    'city' => $city,
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
    // public function test_res_rep_cur_city_get_request()
    // {
    //     $reseller = $this->aReseller();

    //     $this->createTagd($reseller, 'London', 10.50);
    //     $this->createTagd($reseller, 'Paris', 20.50);

    //     $url = static::URL_RES_REP_CURRENCY . '?filter={"city":"London"}';

    //     $response = $this
    //         ->actingAsAReseller($reseller)
    //         ->get($url)
    //         ->assertStatus(200)
    //         ->assertJsonPath('data.GBP.min', 10.5)
    //         ->assertJsonPath('data.GBP.max', 10.5); //Paris is ignored
    // }
}
