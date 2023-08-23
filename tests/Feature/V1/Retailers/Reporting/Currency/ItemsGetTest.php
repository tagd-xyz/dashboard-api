<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Retailers\Reporting\Currency;

use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Tagd;
use Tests\Feature\V1\Retailers\Reporting\Base;

class ItemsGetTest extends Base
{
    private function createTagd(Retailer $retailer): Tagd
    {
        $consumer = $this->aConsumer();

        $tagd = $this->aTagd([
            'consumer' => $consumer,
            'retailer' => $retailer,
        ]);

        $tagd->activate();

        return $tagd;
    }

    /**
     * GET /retailers/reporting/currency
     *
     * @return void
     */
    public function test_ret_rep_cur_items_get_request()
    {
        $retailer = $this->aRetailer();

        $tagd = $this->createTagd($retailer);
        // \Log::info('tagd: ' . $tagd->id);

        $resale = $this->aResale([
            'tagd' => $tagd,
        ]);

        $confirmedResale = $this->aConfirmedResale([
            'tagd' => $resale,
        ]);

        $resale2 = $this->aResale([
            'tagd' => $confirmedResale,
        ]);

        $confirmedResale12 = $this->aConfirmedResale([
            'tagd' => $resale2,
        ]);

        // // dd(Tagd::all()->count());
        // foreach (Tagd::all() as $t) {
        //     // \Log::info($t->status->value . ' ' . $t->id . ' parent:' . $t->parent_id);
        //     \Log::info($t->status->value . ' ' . json_encode($t->stats));
        // }

        $url = static::URL_RET_REP_CURRENCY;

        $response = $this
            ->actingAsARetailer($retailer)
            ->get($url)
            ->assertStatus(200)
            ->assertJsonPath('data.GBP.itemsTransferred', 0); //2);
    }
}
