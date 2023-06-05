<?php

namespace App\Http\V1\Controllers\Retailers\Reporting;

use App\Http\V1\Controllers\Controller as Controller;
use App\Http\V1\Requests\Retailers\Reporting\TagsIssued\Graph as GraphRequest;
use App\Http\V1\Requests\Retailers\Reporting\TagsIssued\Index as IndexRequest;
use App\Http\V1\Resources\Item\Tagd\Collection as TagdCollection;
use Illuminate\Database\Eloquent\Builder;
use Tagd\Core\Models\Item\Tagd as TagdModel;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsRepo;

class TagsIssued extends Controller
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

                $filterStatus = $filter['status'] ?? null;
                $filterCustomerRegistered = $filter['customerRegistered'] ?? null;
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
                    ->when(! is_null($filterCustomerRegistered), function (Builder $query) use ($filterCustomerRegistered) {
                        if ($filterCustomerRegistered) {
                            $query->whereHas('consumer.role');
                        } else {
                            $query->whereDoesntHave('consumer.role');
                        }
                    })
                    ->when(! is_null($filterTransfersCount), function (Builder $query) use ($filterTransfersCount) {
                        switch ($filterTransfersCount) {
                            case 'none':
                                $query->where('stats->count->transferred_consumer', 0);
                                break;
                            case 'one':
                                $query->where('stats->count->transferred_consumer', 1);
                                break;
                            case 'two':
                                $query->where('stats->count->transferred_consumer', 2);
                                break;
                            case 'three':
                                $query->where('stats->count->transferred_consumer', 3);
                                break;
                            case 'four':
                                $query->where('stats->count->transferred_consumer', 4);
                                break;
                            case 'five_or_more':
                                $query->where('stats->count->transferred_consumer', '>=', 5);
                                break;
                        }
                    })
                    ->when(! is_null($filterBrands), function (Builder $query) use ($filterBrands) {
                        $query->whereHas('item', function ($query) use ($filterBrands) {
                            $query->whereIn('properties->brand', $filterBrands);
                        });
                    })
                    ->when(! is_null($filterStatus), function (Builder $query) use ($filterStatus) {
                        $query->whereStatus($filterStatus);
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
        $filterStatus = $filter['status'] ?? null;
        $filterCustomerRegistered = $filter['customerRegistered'] ?? null;
        $filterTransfersCount = $filter['transfersCount'] ?? null;
        $filterBrands = $filter['brands'] ?? null;

        $data = TagdModel::query()
            ->selectRaw(
                str_replace("\n", '',
                "DATE_ADD(
                    '$since',
                    INTERVAL FLOOR(
                      TIMESTAMPDIFF(MINUTE, '$since', created_at) / $minutes
                    ) * $minutes minute
                  ) AS datetime_interval,
                  COUNT(*) AS total"
                ), [
                ]
            )
            ->where('created_at', '<', $until)
            ->whereNull('parent_id')
            ->whereHas('item', function (Builder $query) use ($actingAs) {
                $query->where('retailer_id', $actingAs->id);
            })
            ->whereBetween('created_at', [
                $request->get(IndexRequest::DATE_FROM),
                $request->get(IndexRequest::DATE_TO),
            ])
            ->when(! is_null($filterCustomerRegistered), function (Builder $query) use ($filterCustomerRegistered) {
                if ($filterCustomerRegistered) {
                    $query->whereHas('consumer.role');
                } else {
                    $query->whereDoesntHave('consumer.role');
                }
            })
            ->when(! is_null($filterTransfersCount), function (Builder $query) use ($filterTransfersCount) {
                switch ($filterTransfersCount) {
                    case 'none':
                        $query->where('stats->count->transferred_consumer', 0);
                        break;
                    case 'one':
                        $query->where('stats->count->transferred_consumer', 1);
                        break;
                    case 'two':
                        $query->where('stats->count->transferred_consumer', 2);
                        break;
                    case 'three':
                        $query->where('stats->count->transferred_consumer', 3);
                        break;
                    case 'four':
                        $query->where('stats->count->transferred_consumer', 4);
                        break;
                    case 'five_or_more':
                        $query->where('stats->count->transferred_consumer', '>=', 5);
                        break;
                }
            })
            ->when(! is_null($filterBrands), function (Builder $query) use ($filterBrands) {
                $query->whereHas('item', function ($query) use ($filterBrands) {
                    $query->whereIn('properties->brand', $filterBrands);
                });
            })
            ->when(! is_null($filterStatus), function (Builder $query) use ($filterStatus) {
                $query->whereStatus($filterStatus);
            })
            ->groupBy('datetime_interval')
            ->orderBy('datetime_interval')
            ->get();

        return response()->withData(
            $data
        );
    }
}

// select
// DATE_ADD(
//   '1000-01-01 00:00:00',
//   Interval FLOOR(
//     TIMESTAMPDIFF(MINUTE, '1000-01-01 00:00:00', created_at) / 2880
//   ) * 2880 minute
// ) AS datetime_interval,
// COUNT(*) AS total
// from `tagds`
// where `parent_id` is null
// group by `datetime_interval`
// order by `datetime_interval` asc
