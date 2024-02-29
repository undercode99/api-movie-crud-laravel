<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(
            function (Throwable $e) {
                //
            }
        );

        $this->renderable(
            function (Throwable $e) {
                if ($e instanceof ExceptionService) {
                    $data = [
                        'message' => $e->getMessage(),
                        'success' => false,
                        'code' => $e->getCode(),
                    ];
                    if ($e->getErrors()) {
                        $data['errors'] = $e->getErrors();
                    }

                    return response()->json(
                        $data
                    )->setStatusCode($e->getCode());
                }

                // not found err
                if ($e instanceof NotFoundHttpException) {
                    return response()->json([
                        'message' => 'Resource not found',
                        'success' => false,
                        'code' => 404,
                    ])->setStatusCode(404);
                }
            }

        );
    }
}
