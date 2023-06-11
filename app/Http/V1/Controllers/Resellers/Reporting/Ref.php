<?php

namespace App\Http\V1\Controllers\Resellers\Reporting;

use App\Http\V1\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Tagd\Core\Models\Item\Item as ItemModel;

class Ref extends Controller
{
    protected function brands(Request $request)
    {
        $actingAs = $this->actingAs($request);

        $brands = ItemModel::query()
            // ->where('retailer_id', $actingAs->id)
            ->whereNotNull('properties->brand')
            ->select('properties->brand as name')
            ->distinct('name')
            ->get();

        return response()->withData(
            $brands
        );
    }
}
