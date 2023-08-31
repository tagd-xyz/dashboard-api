<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Resellers\Reporting\Currency;

use Tests\Feature\V1\Resellers\Reporting\Base;

class BasicGetTest extends Base
{
    /**
     * GET /resellers/reporting/currency
     *
     * @return void
     */
    public function test_res_rep_cur_basic_get_no_auth_request()
    {
        $response = $this
            // ->actingAsAReseller($reseller)
            ->get(static::URL_RES_REP_CURRENCY)
            ->assertStatus(403);
    }

    /**
     * GET /resellers/reporting/currency
     *
     * @return void
     */
    public function test_res_rep_cur_basic_get_request()
    {
        $reseller = $this->aReseller();

        $response = $this
            ->actingAsAReseller($reseller)
            ->get(static::URL_RES_REP_CURRENCY)
            ->assertStatus(200);
    }

    /**
     * GET /resellers/reporting/currency
     *
     * @return void
     */
    public function test_res_rep_cur_basic_get_simple_request()
    {
        $reseller = $this->aReseller();

        $tagd = $this->aTagd([
            'reseller' => $reseller,
        ]);

        $tagd->activate();

        $response = $this
            ->actingAsAReseller($reseller)
            ->get(static::URL_RES_REP_CURRENCY)
            ->assertStatus(200);
    }
}
