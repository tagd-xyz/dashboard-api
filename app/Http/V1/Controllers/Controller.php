<?php

namespace App\Http\V1\Controllers;

use App\Http\Middleware\ExpectsActAs;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\User\Role;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    protected function actingAs(Request $request): Retailer|Reseller|Consumer
    {
        // $parsed = ExpectsActAs::parse($request);

        // throw_if(! $parsed, new BadRequestHttpException(
        //     'Invalid ' . ExpectsActAs::HEADER_KEY . ' header'
        // ));

        // extract($parsed);

        $user = Auth::user();

        return $user->actorsOfType(Role::RESELLER)->first();
    }
}
