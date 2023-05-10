<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Resources\Me as MeResource;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Me extends BaseController
{
    /**
     * Get basic status info
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $user = Auth::user();

        return response()->withData(
            new MeResource($user)
        );
    }
}
