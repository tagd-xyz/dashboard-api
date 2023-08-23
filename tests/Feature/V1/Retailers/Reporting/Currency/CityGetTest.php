<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Retailers\Reporting\Currency;

use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Tagd;
use Tests\Feature\V1\Retailers\Reporting\Base;

class CityGetTest extends Base
{
    private function createTagd(Retailer $retailer, string $city, float $amount): Tagd
    {
        $consumer = $this->aConsumer();

        $tagd = $this->aTagd([
            'consumer' => $consumer,
            'retailer' => $retailer,
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
     * GET /retailers/reporting/currency
     *
     * @return void
     */
    public function test_ret_rep_cur_city_get_request()
    {
        $retailer = $this->aRetailer();

        $this->createTagd($retailer, 'London', 10.50);
        $this->createTagd($retailer, 'Paris', 20.50);

        $url = static::URL_RET_REP_CURRENCY . '?filter={"city":"London"}';

        $response = $this
            ->actingAsARetailer($retailer)
            ->get($url)
            ->assertStatus(200)
            ->assertJsonPath('data.GBP.min', 10.5)
            ->assertJsonPath('data.GBP.max', 10.5); //Paris is ignored
    }
}
