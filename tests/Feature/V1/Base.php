<?php

namespace Tests\Feature\V1;

use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tests\TestCase;

abstract class Base extends TestCase
{
    use UsesFactories;

    public const URL_V1 = '/api/v1';

    public const URL_STATUS = '/api/v1/status';

    public const URL_ME = '/api/v1/me';

    /**
     * setUp any test
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);

        $this->setupFactories();
    }
}
