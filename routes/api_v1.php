<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\App\Http\V1\Controllers')->group(function () {
    Route::permanentRedirect('/', '/api/v1/status');

    Route::middleware('guest')->group(function () {
        Route::get('status', 'Status@index')
            ->name('status');
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', 'Me@show');

        Route::prefix('retailers')
            ->group(function () {
                Route::prefix('reporting')
                    ->namespace('\App\Http\V1\Controllers\Retailers\Reporting')
                    ->group(function () {
                        Route::prefix('ref')
                            ->group(function () {
                                Route::get('brands', 'Ref@brands');
                            });

                        Route::prefix('avg-resale-value')
                            ->group(function () {
                                Route::get('graph', 'AvgResaleValue@graph');
                            });

                        Route::prefix('return-rate')
                            ->group(function () {
                                Route::get('graph', 'ReturnRate@graph');
                            });

                        Route::prefix('popular-types')
                            ->group(function () {
                                Route::get('list', 'PopularTypes@index');
                            });

                        Route::prefix('resale-frequency')
                            ->group(function () {
                                Route::get('list', 'ResaleFrequency@index');
                            });

                        Route::prefix('popular-resellers')
                            ->group(function () {
                                Route::get('list', 'PopularResellers@index');
                            });

                        Route::prefix('tags-issued')
                            ->group(function () {
                                Route::get('list', 'TagsIssued@index');
                                Route::get('graph', 'TagsIssued@graph');
                            });

                        Route::prefix('time-to-transfer')
                            ->group(function () {
                                Route::get('list', 'TimeToTransfer@index');
                                Route::get('graph', 'TimeToTransfer@graph');
                            });

                        Route::prefix('fraud-report')
                            ->group(function () {
                                Route::get('list', 'FraudReport@index');
                                Route::get('graph', 'FraudReport@graph');
                            });

                        Route::prefix('currency')
                            ->group(function () {
                                Route::get('/', 'Currency@index');
                            });
                    });
            });

        Route::prefix('resellers')
            ->group(function () {
                Route::prefix('reporting')
                    ->namespace('\App\Http\V1\Controllers\Resellers\Reporting')
                    ->group(function () {
                        Route::prefix('ref')
                            ->group(function () {
                                Route::get('brands', 'Ref@brands');
                            });

                        Route::prefix('avg-resale-value')
                            ->group(function () {
                                Route::get('graph', 'AvgResaleValue@graph');
                            });

                        Route::prefix('popular-types')
                            ->group(function () {
                                Route::get('list', 'PopularTypes@index');
                            });

                        Route::prefix('resale-frequency')
                            ->group(function () {
                                Route::get('list', 'ResaleFrequency@index');
                            });

                        Route::prefix('popular-resellers')
                            ->group(function () {
                                Route::get('list', 'PopularResellers@index');
                            });

                        Route::prefix('time-to-transfer')
                            ->group(function () {
                                Route::get('list', 'TimeToTransfer@index');
                                Route::get('graph', 'TimeToTransfer@graph');
                            });

                        Route::prefix('fraud-report')
                            ->group(function () {
                                Route::get('list', 'FraudReport@index');
                                Route::get('graph', 'FraudReport@graph');
                            });

                        Route::prefix('currency')
                            ->group(function () {
                                Route::get('/', 'Currency@index');
                            });
                    });
            });
    });
});
