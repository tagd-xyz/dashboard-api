<?php

namespace App\Exceptions;

use Firebase\JWT\ExpiredException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tagd\Core\Support\Repository\Exceptions\NotFound as RepoNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
        });

        $this->renderable(function (\Exception $e, $request) {
            switch (get_class($e)) {
                case BadRequestHttpException::class:
                    return response()->withError([
                        'message' => $e->getMessage(),
                    ], 400);

                case ExpiredException::class:
                case AuthenticationException::class:
                case AccessDeniedHttpException::class:
                    return response()->withError([
                        'message' => $e->getMessage(),
                    ], 403);

                case ModelNotFoundException::class:
                case RepoNotFoundException::class:
                case NotFoundHttpException::class:
                    return response()->withError([
                        'message' => $e->getMessage(),
                    ], 404);

                case MethodNotAllowedHttpException::class:
                    return response()->withError([
                        'message' => $e->getMessage(),
                    ], 405);

                case ValidationException::class:
                    return response()->withError([
                        'message' => $e->getMessage(),
                        'details' => $e->errors(),
                    ], 422);

                default:
                    return response()->withError([
                        'message' => $e->getMessage(),
                    ], 500);
            }
        });
    }
}
