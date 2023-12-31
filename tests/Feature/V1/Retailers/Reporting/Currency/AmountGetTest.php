<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Retailers\Reporting\Currency;

use Tagd\Core\Models\Actor\Retailer;
use Tests\Feature\V1\Retailers\Reporting\Base;

class AmountGetTest extends Base
{
    private function createTagds(Retailer $retailer, array $amounts): array
    {
        $consumer = $this->aConsumer();

        $tags = [];
        foreach ($amounts as $amount) {
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

            $tagds[] = $tagd;
        }

        return $tags;
    }

    private function ret_rep_cur_amount_get_simple_x_request(array $amounts)
    {
        $retailer = $this->aRetailer();

        $tagds = $this->createTagds($retailer, $amounts);

        $response = $this
            ->actingAsARetailer($retailer)
            ->get(static::URL_RET_REP_CURRENCY)
            ->assertStatus(200)
            ->assertJsonPath('data', $this->convertArray([
                'GBP' => [
                    'min' => $this->calculateMin($amounts),
                    'max' => $this->calculateMax($amounts),
                    'mean' => $this->calculateMean($amounts),
                    'median' => $this->calculateMedian($amounts),
                    'stdDev' => $this->calculateStandardDeviation($amounts),
                    'quantiles' => [
                        'q1' => [
                            'value' => $this->calculateQuantile($amounts, 1),
                            'items' => 1,
                        ],
                        'q2' => [
                            'value' => $this->calculateQuantile($amounts, 2),
                            'items' => (count($amounts) == 1) ? 1 : 0,
                        ],
                        'q3' => [
                            'value' => $this->calculateQuantile($amounts, 3),
                            'items' => (count($amounts) == 1) ? 1 : 0,
                        ],
                        'q4' => [
                            'value' => $this->calculateQuantile($amounts, 4),
                            'items' => (count($amounts) == 1) ? 1 : 0,
                        ],
                    ],
                    'itemsTransferred' => 0,
                    'itemsAffected' => count($amounts),
                ],
            ]));
    }

    /**
     * GET /retailers/reporting/currency
     *
     * @return void
     */
    public function test_ret_rep_cur_amount_get_simple_1_request()
    {
        $this->ret_rep_cur_amount_get_simple_x_request([
            10.50,
        ]);
    }

    /**
     * GET /retailers/reporting/currency
     *
     * @return void
     */
    public function test_ret_rep_cur_amount_get_simple_2_request()
    {
        $this->ret_rep_cur_amount_get_simple_x_request([
            10.50,
            20.50,
        ]);
    }

    /**
     * GET /retailers/reporting/currency
     *
     * @return void
     */
    public function test_ret_rep_cur_amount_get_simple_3_request()
    {
        $this->ret_rep_cur_amount_get_simple_x_request([
            10.50,
            20.50,
            30.50,
        ]);
    }

    /**
     * GET /retailers/reporting/currency
     *
     * @return void
     */
    public function test_ret_rep_cur_amount_get_simple_4_request()
    {
        $this->ret_rep_cur_amount_get_simple_x_request([
            10.50,
            20.50,
            30.50,
            40.50,
        ]);
    }

    /**
     * GET /retailers/reporting/currency
     *
     * @return void
     */
    public function test_ret_rep_cur_amount_get_simple_5_request()
    {
        $this->ret_rep_cur_amount_get_simple_x_request([
            10.50,
            20.50,
            30.50,
            40.50,
            50.50,
        ]);
    }

    // /**
    //  * GET /retailers/reporting/currency
    //  *
    //  * @return void
    //  */
    // public function test_ret_rep_cur_amount_get_simple_10_request()
    // {
    //     $this->ret_rep_cur_amount_get_simple_x_request([
    //         10.50,
    //         20.50,
    //         30.50,
    //         40.50,
    //         50.50,
    //         60.50,
    //         70.50,
    //         80.50,
    //         90.50,
    //         100.50,
    //     ]);
    // }
}
