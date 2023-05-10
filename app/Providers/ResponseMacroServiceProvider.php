<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('withData', function ($data, $status = 200) {
            return Response::json([
                'status' => $status,
                'data' => $data,
            ], $status);
        });

        Response::macro('withError', function ($error, $status = 500) {
            return Response::json([
                'status' => $status,
                'error' => $error,
            ], $status);
        });
    }
}
