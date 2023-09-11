<?php

namespace App\Http\V1\Controllers\Retailers\Reporting;

use App\Http\V1\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tagd\Core\Models\Item\Tagd as TagdModel;
use Tagd\Core\Models\Item\TagdStatus;

class PopularResellers extends Controller
{
    protected function index(Request $request)
    {
        $actingAs = $this->actingAs($request);

        $since = Carbon::now()->subMonths(6);
        $until = Carbon::now();

        $data = TagdModel::join('resellers', 'resellers.id', '=', 'reseller_id')
            // ->leftJoin('resellers_images', 'resellers_images.reseller_id', '=', 'resellers.id')
            // ->leftJoin('uploads', 'uploads.id', '=', 'resellers_images.upload_id')
            ->where('status', TagdStatus::TRANSFERRED)
            ->whereBetween('tagds.created_at', [$since, $until])
            ->selectRaw('resellers.id, resellers.name, count(*) as total')
            ->groupBy('reseller_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return response()->withData(
            $data
        );
    }
}
