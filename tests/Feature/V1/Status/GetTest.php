<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Status;

use Tests\Feature\V1\Base;

class GetTest extends Base
{
    /**
     * GET /status
     *
     * @return void
     */
    public function test_status_get_request()
    {
        $response = $this
            ->get(static::URL_STATUS)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'now',
                ],
            ]);
    }

    /**
     * GET /
     *
     * @return void
     */
    public function test_root_get_request()
    {
        $response = $this
            ->get('/')
            ->assertStatus(301);

        $response = $this
            ->get('/api')
            ->assertStatus(301);

        $response = $this
            ->get('/api/v1')
            ->assertStatus(301);
    }
}
