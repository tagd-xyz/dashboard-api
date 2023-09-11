<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Resellers\Reporting\Currency;

use Tagd\Core\Models\Actor\Reseller;
use Tests\Feature\V1\Resellers\Reporting\Base;

class AmountGetTest extends Base
{
    private function createTagds(Reseller $reseller, array $amounts): array
    {
        $consumer = $this->aConsumer();

        $tags = [];
        foreach ($amounts as $amount) {
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

            $tagds[] = $tagd;
        }

        return $tags;
    }

    private function res_rep_cur_amount_get_simple_x_request(array $amounts)
    {
        $reseller = $this->aReseller();

        $tagds = $this->createTagds($reseller, $amounts);

        $response = $this
            ->actingAsAReseller($reseller)
            ->get(static::URL_RES_REP_CURRENCY)
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
     * GET /resellers/reporting/currency
     *
     * @return void
     */
    // public function test_res_rep_cur_amount_get_simple_1_request()
    // {
    //     $this->res_rep_cur_amount_get_simple_x_request([
    //         10.50,
    //     ]);
    // }

    /**
     * GET /resellers/reporting/currency
     *
     * @return void
     */
    // public function test_res_rep_cur_amount_get_simple_2_request()
    // {
    //     $this->res_rep_cur_amount_get_simple_x_request([
    //         10.50,
    //         20.50,
    //     ]);
    // }

    /**
     * GET /resellers/reporting/currency
     *
     * @return void
     */
    // public function test_res_rep_cur_amount_get_simple_3_request()
    // {
    //     $this->res_rep_cur_amount_get_simple_x_request([
    //         10.50,
    //         20.50,
    //         30.50,
    //     ]);
    // }

    /**
     * GET /resellers/reporting/currency
     *
     * @return void
     */
    // public function test_res_rep_cur_amount_get_simple_4_request()
    // {
    //     $this->res_rep_cur_amount_get_simple_x_request([
    //         10.50,
    //         20.50,
    //         30.50,
    //         40.50,
    //     ]);
    // }

    /**
     * GET /resellers/reporting/currency
     *
     * @return void
     */
    // public function test_res_rep_cur_amount_get_simple_5_request()
    // {
    //     $this->res_rep_cur_amount_get_simple_x_request([
    //         10.50,
    //         20.50,
    //         30.50,
    //         40.50,
    //         50.50,
    //     ]);
    // }

    // /**
    //  * GET /resellers/reporting/currency
    //  *
    //  * @return void
    //  */
    // public function test_res_rep_cur_amount_get_simple_10_request()
    // {
    //     $this->res_rep_cur_amount_get_simple_x_request([
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
