<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Retailers\Reporting\Currency;

use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Tagd;
use Tests\Feature\V1\Retailers\Reporting\Base;

class ModelGetTest extends Base
{
    private function createTagd(Retailer $retailer, string $model, float $amount): Tagd
    {
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

        $tagd->item->update([
            'properties' => [
                ...$tagd->item->properties,
                'model' => $model,
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
    public function test_ret_rep_cur_model_get_request()
    {
        $retailer = $this->aRetailer();

        $tagds = $this->createTagd($retailer, 'model_first', 10.50);
        $tagds = $this->createTagd($retailer, 'model_second', 20.50);

        $url = static::URL_RET_REP_CURRENCY . '?filter={"model":"first"}';

        $response = $this
            ->actingAsARetailer($retailer)
            ->get($url)
            ->assertStatus(200)
            ->assertJsonPath('data.GBP.min', 10.5)
            ->assertJsonPath('data.GBP.max', 10.5); //second is excluded
    }
}
