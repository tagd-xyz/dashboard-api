<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Me;

use Tagd\Core\Tests\Traits\NeedsResellers;

class GetTest extends Base
{
    use NeedsResellers;

    /**
     * GET /status
     *
     * @return void
     */
    public function test_me_get_request()
    {
        $reseller = $this->aReseller();

        $response = $this
            ->actingAsAReseller($reseller)
            ->get(static::URL_ME)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'email',
                    'name',
                    'actors',
                ],
            ]);
    }

    /**
     * GET /status
     *
     * @return void
     */
    public function test_me_get_request_noauth()
    {
        $response = $this
            ->get(static::URL_ME)
            ->assertStatus(403);
    }
}
