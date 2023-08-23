<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Resellers\Reporting\Currency;

use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Item\Tagd;
use Tests\Feature\V1\Resellers\Reporting\Base;

class ItemsGetTest extends Base
{
    private function createTagd(Reseller $reseller): Tagd
    {
        $consumer = $this->aConsumer();

        $tagd = $this->aTagd([
            // 'consumer' => $consumer,
            'reseller' => $reseller,
        ]);

        $tagd->activate();

        return $tagd;
    }

    /**
     * GET /resellers/reporting/currency
     *
     * @return void
     */
    // public function test_res_rep_cur_items_get_request()
    // {
    //     $reseller = $this->aReseller();

    //     $tagd = $this->createTagd($reseller);
    //     // \Log::info('tagd: ' . $tagd->id);

    //     $resale = $this->aResale([
    //         'tagd' => $tagd,
    //     ]);

    //     $confirmedResale = $this->aConfirmedResale([
    //         'tagd' => $resale,
    //     ]);

    //     $resale2 = $this->aResale([
    //         'tagd' => $confirmedResale,
    //     ]);

    //     $confirmedResale12 = $this->aConfirmedResale([
    //         'tagd' => $resale2,
    //     ]);

    //     // // dd(Tagd::all()->count());
    //     // foreach (Tagd::all() as $t) {
    //     //     // \Log::info($t->status->value . ' ' . $t->id . ' parent:' . $t->parent_id);
    //     //     \Log::info($t->status->value . ' ' . json_encode($t->stats));
    //     // }

    //     $url = static::URL_RES_REP_CURRENCY;

    //     $response = $this
    //         ->actingAsAReseller($reseller)
    //         ->get($url)
    //         ->assertStatus(200)
    //         ->assertJsonPath('data.GBP.itemsTransferred', 0); //2);
    // }
}
