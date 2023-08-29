<?php

namespace App\Http\V1\Controllers\Retailers\Reporting;

use App\Http\V1\Controllers\Controller as Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tagd\Core\Models\Item\Tagd as TagdModel;
use Tagd\Core\Models\Item\TagdStatus;

class PopularTypes extends Controller
{
    protected function index(Request $request)
    {
        $actingAs = $this->actingAs($request);

        $since = Carbon::now()->subMonths(6);
        $until = Carbon::now();

        $data = TagdModel::join('items', 'items.id', '=', 'tagds.item_id')
            ->join('item_types', 'item_types.id', '=', 'items.type_id')
            ->whereNull('tagds.parent_id')
            ->whereHas('item', function (Builder $query) use ($actingAs) {
                $query->where('retailer_id', $actingAs->id);
            })
            ->whereHas('children', function (Builder $query) {
                $query->where('status', TagdStatus::TRANSFERRED);
            })
            ->whereBetween('tagds.created_at', [$since, $until])
            ->selectRaw('item_types.id, item_types.name, count(*) as total')
            ->groupBy('items.type_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return response()->withData(
            $data
        );
    }
}
