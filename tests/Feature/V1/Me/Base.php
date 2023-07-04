<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Me;

use Tagd\Core\Tests\Traits\NeedsDatabase;
use Tagd\Core\Tests\Traits\NeedsResellers;

abstract class Base extends \Tests\Feature\V1\Base
{
    use NeedsDatabase, NeedsResellers;
}
