<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Resellers\Reporting\Currency;

use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Item\Tagd;
use Tests\Feature\V1\Resellers\Reporting\Base;

class BrandGetTest extends Base
{
    private function createTagd(Reseller $reseller, string $brand, float $amount): Tagd
    {
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

        $tagd->item->update([
            'properties' => [
                ...$tagd->item->properties,
                'brand' => $brand,
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
    // public function test_res_rep_cur_brand_get_request()
    // {
    //     $reseller = $this->aReseller();

    //     $tagds = $this->createTagd($reseller, 'Adidas', 10.50);
    //     $tagds = $this->createTagd($reseller, 'Nike', 20.50);

    //     $url = static::URL_RES_REP_CURRENCY . '?filter={"brands":["Adidas"]}';

    //     $response = $this
    //         ->actingAsAReseller($reseller)
    //         ->get($url)
    //         ->assertStatus(200)
    //         ->assertJsonPath('data.GBP.min', 10.5)
    //         ->assertJsonPath('data.GBP.max', 10.5); //expensive Nike is excluded
    // }
}
