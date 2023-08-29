<?php

namespace App\Http\V1\Controllers\Resellers\Reporting;

use App\Http\V1\Controllers\Controller as Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tagd\Core\Models\Item\Tagd as TagdModel;
use Tagd\Core\Models\Item\TagdStatus;

class ResaleFrequency extends Controller
{
    protected function index(Request $request)
    {
        $actingAs = $this->actingAs($request);

        $since = Carbon::now()->subMonths(6);
        $until = Carbon::now();

        $data = TagdModel::join('items', 'items.id', '=', 'tagds.item_id')
            ->join('item_types', 'item_types.id', '=', 'items.type_id')
            ->where('reseller_id', $actingAs->id)
            ->whereHas('children', function (Builder $query) {
                $query->where('status', TagdStatus::TRANSFERRED);
            })
            ->whereBetween('tagds.created_at', [$since, $until])
            ->selectRaw('items.id, item_types.name as type, items.name, items.description, items.properties, count(*) as total')
            ->groupBy('items.id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return response()->withData(
            $data
        );
    }
}
