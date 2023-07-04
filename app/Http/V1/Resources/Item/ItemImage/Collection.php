<?php

namespace App\Http\V1\Resources\Item\ItemImage;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Collection extends ResourceCollection
{
    // use \App\Http\V1\Resources\Traits\HasPagination;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = Single::class;
}
