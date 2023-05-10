<?php

namespace Tests\Feature\V1;

abstract class Base extends TestCase
{
    public const URL_V1 = '/api/v1';

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
