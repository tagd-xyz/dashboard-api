<?php

namespace App\Http\V1\Controllers\Resellers\Reporting;

use App\Http\V1\Controllers\Controller as Controller;
use App\Http\V1\Requests\Retailers\Reporting\AvgResaleValue\Graph as GraphRequest;
use Illuminate\Support\Carbon;
use Tagd\Core\Models\Item\Tagd as TagdModel;
use Tagd\Core\Models\Item\TagdStatus;

class AvgResaleValue extends Controller
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

            $partial = TagdModel::whereBetween('tagds.created_at', [$since, $until])
                ->where('status', TagdStatus::TRANSFERRED)
                ->where('reseller_id', $actingAs->id)
                ->avg('stats->avgResaleDiffPerc');

            $data[] = [
                'since' => $since->format('Y-m-d'),
                'until' => $until->format('Y-m-d'),
                'value' => $partial,
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
