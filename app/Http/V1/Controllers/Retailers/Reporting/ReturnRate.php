<?php

namespace App\Http\V1\Controllers\Retailers\Reporting;

use App\Http\V1\Controllers\Controller as Controller;
use App\Http\V1\Requests\Retailers\Reporting\AvgResaleValue\Graph as GraphRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Tagd\Core\Models\Item\Tagd as TagdModel;
use Tagd\Core\Models\Item\TagdStatus;

class ReturnRate extends Controller
{
    protected function graph(GraphRequest $request)
    {
        $actingAs = $this->actingAs($request);

        $monthsAgo = $request->get(GraphRequest::MONTHS_AGO, 6);

        $since = Carbon::now()
            ->startOfMonth()
            ->subMonths($monthsAgo)
            ->startOfDay();

        $data = [];
        for ($i = 0; $i < $monthsAgo; $i++) {
            $until = $since->clone()->endOfMonth()->endOfDay();

            $mineTotal = TagdModel::whereBetween('tagds.created_at', [$since, $until])
                ->whereNull('parent_id')
                ->whereHas('item', function (Builder $query) use ($actingAs) {
                    $query->where('retailer_id', $actingAs->id);
                })
                ->count();

            $mineReturned = TagdModel::whereBetween('tagds.created_at', [$since, $until])
                ->where('status', TagdStatus::RETURNED)
                ->whereNull('parent_id')
                ->whereHas('item', function (Builder $query) use ($actingAs) {
                    $query->where('retailer_id', $actingAs->id);
                })
                ->count();

            $othersTotal = TagdModel::whereBetween('tagds.created_at', [$since, $until])
                ->whereNull('parent_id')
                ->whereHas('item', function (Builder $query) use ($actingAs) {
                    $query->where('retailer_id', '!=', $actingAs->id);
                })
                ->count();

            $othersReturned = TagdModel::whereBetween('tagds.created_at', [$since, $until])
                ->where('status', TagdStatus::RETURNED)
                ->whereNull('parent_id')
                ->whereHas('item', function (Builder $query) use ($actingAs) {
                    $query->where('retailer_id', '!=', $actingAs->id);
                })
                ->count();

            $data[] = [
                'since' => $since->format('Y-m-d'),
                'until' => $until->format('Y-m-d'),
                'mineTotal' => $mineTotal,
                'mineReturned' => $mineReturned,
                'mineRate' => $mineTotal ? round($mineReturned / $mineTotal * 100, 2) : 0,
                'othersTotal' => $othersTotal,
                'othersReturned' => $othersReturned,
                'othersRate' => $othersTotal ? round($othersReturned / $othersTotal * 100, 2) : 0,
            ];

            $since->addMonth();
            $since->startOfMonth();
            $since->startOfDay();
        }

        return response()->withData(
            $data
        );
    }
}
