<?php

namespace App\Http\V1\Controllers\Retailers\Reporting;

use App\Http\V1\Controllers\Controller as Controller;
use App\Http\V1\Requests\Retailers\Reporting\TimeToTransfer\Graph as GraphRequest;
use App\Http\V1\Requests\Retailers\Reporting\TimeToTransfer\Index as IndexRequest;
use App\Http\V1\Resources\Item\Tagd\Collection as TagdCollection;
use Illuminate\Database\Eloquent\Builder;
use Tagd\Core\Models\Item\Tagd as TagdModel;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsRepo;

class TimeToTransfer extends Controller
{
    protected function index(IndexRequest $request, TagdsRepo $repo)
    {
        $actingAs = $this->actingAs($request);

        $data = $repo->allPaginated([
            'perPage' => $request->get(IndexRequest::PER_PAGE, 2),
            'page' => $request->get(IndexRequest::PAGE, 1),
            // 'orderBy' => 'created_at',
            // 'direction' => $request->get(IndexRequest::DIRECTION, 'asc'),
            'relations' => [
                'item',
                'item.images',
                'item.images.upload',
                'consumer',
                'consumer.role',
            ],
            'append' => [
                'children_count',
            ],
            'filterFunc' => function ($query) use ($actingAs, $request) {
                $filter = json_decode($request->get(IndexRequest::FILTER, '{}'), true);

                $filterTransfersCount = $filter['transfersCount'] ?? null;
                $filterBrands = $filter['brands'] ?? null;

                $query
                    ->whereNull('parent_id')
                    ->whereHas('item', function (Builder $query) use ($actingAs) {
                        $query->where('retailer_id', $actingAs->id);
                    })
                    ->whereBetween('created_at', [
                        $request->get(IndexRequest::DATE_FROM),
                        $request->get(IndexRequest::DATE_TO),
                    ])
                    ->when(! is_null($filterTransfersCount), function (Builder $query) use ($filterTransfersCount) {
                        $query->whereNotNull('stats->ttt->n' . $filterTransfersCount);
                    })
                    ->when(! is_null($filterBrands), function (Builder $query) use ($filterBrands) {
                        $query->whereHas('item', function ($query) use ($filterBrands) {
                            $query->whereIn('properties->brand', $filterBrands);
                        });
                    });
            },
        ]);

        return response()->withData(
            new TagdCollection($data)
        );
    }

    protected function graph(GraphRequest $request)
    {
        $actingAs = $this->actingAs($request);

        $since = $request->get(IndexRequest::DATE_FROM);
        $until = $request->get(IndexRequest::DATE_TO);
        $minutes = 1440;

        $filter = json_decode($request->get(IndexRequest::FILTER, '{}'), true);
        $filterTransfersCount = $filter['transfersCount'] ?? null;
        $filterBrands = $filter['brands'] ?? null;

        // $data = TagdModel::query()
        //     ->select('stats->ttt as ttt')
        //     ->whereNull('parent_id')
        //     ->get();

        // dd($data);

        $raw = "json_unquote(json_extract(`stats`, '$.\"ttt\".\"n" . $filterTransfersCount . "\"')) div 1440";

        $data = TagdModel::select(
                \DB::raw($raw . ' as t'),
                \DB::raw('count(*) as total')
            )
            ->whereNull('parent_id')
            ->whereBetween('created_at', [
                $request->get(IndexRequest::DATE_FROM),
                $request->get(IndexRequest::DATE_TO),
            ])
            ->when(! is_null($filterTransfersCount), function (Builder $query) use ($filterTransfersCount) {
                $query->whereNotNull('stats->ttt->n' . $filterTransfersCount);
            })
            ->when(! is_null($filterBrands), function (Builder $query) use ($filterBrands) {
                $query->whereHas('item', function ($query) use ($filterBrands) {
                    $query->whereIn('properties->brand', $filterBrands);
                });
            })
            ->groupByRaw($raw)
            ->get();

        return response()->withData(
            $data
        );
    }
}
