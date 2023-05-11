<?php

namespace App\Http\V1\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    protected function actingAs(Request $request): Retailer|Reseller|Consumer
    {
        $user = Auth::user();

        return $user->actorsOfType($user->tenant)->first();
    }
}
