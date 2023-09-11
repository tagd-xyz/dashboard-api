<?php

namespace App\Http\V1\Controllers\Retailers\Reporting;

use App\Http\V1\Controllers\Controller as Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Tagd\Core\Models\Item\Item as ItemModel;
use Tagd\Core\Models\Item\Tagd as TagdModel;

class Ref extends Controller
{
    protected function brands(Request $request)
    {
        $actingAs = $this->actingAs($request);

        $brands = ItemModel::query()
            ->where('retailer_id', $actingAs->id)
            ->whereNotNull('properties->brand')
            ->select('properties->brand as name')
            ->distinct('name')
            ->get();

        return response()->withData(
            $brands
        );
    }

    protected function countries(Request $request)
    {
        $actingAs = $this->actingAs($request);

        $countries = TagdModel::query()
            ->whereHas('item', function (Builder $query) use ($actingAs) {
                $query->where('retailer_id', $actingAs->id);
            })
            ->whereNotNull('meta->location->country')
            ->select('meta->location->country as code')
            ->distinct('code')
            ->join('countries', 'countries.code', '=', 'meta->location->country')
            ->select(['countries.code', 'countries.name'])
            ->get();

        return response()->withData(
            $countries
        );
    }
}
