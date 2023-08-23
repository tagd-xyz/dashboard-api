<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Retailers\Reporting\Currency;

use Tests\Feature\V1\Retailers\Reporting\Base;

class BasicGetTest extends Base
{
    /**
     * GET /retailers/reporting/currency
     *
     * @return void
     */
    public function test_ret_rep_cur_basic_get_no_auth_request()
    {
        $response = $this
            // ->actingAsARetailer($retailer)
            ->get(static::URL_RET_REP_CURRENCY)
            ->assertStatus(403);
    }

    /**
     * GET /retailers/reporting/currency
     *
     * @return void
     */
    public function test_ret_rep_cur_basic_get_request()
    {
        $retailer = $this->aRetailer();

        $response = $this
            ->actingAsARetailer($retailer)
            ->get(static::URL_RET_REP_CURRENCY)
            ->assertStatus(200);
    }

    /**
     * GET /retailers/reporting/currency
     *
     * @return void
     */
    public function test_ret_rep_cur_basic_get_simple_request()
    {
        $retailer = $this->aRetailer();
        $consumer = $this->aConsumer();

        $tagd = $this->aTagd([
            'consumer' => $consumer,
            'retailer' => $retailer,
        ]);

        $tagd->activate();

        $response = $this
            ->actingAsARetailer($retailer)
            ->get(static::URL_RET_REP_CURRENCY)
            ->assertStatus(200);
    }
}
