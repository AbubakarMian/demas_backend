<?php

namespace App\Exceptions;

use App\Libraries\APIResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use APIResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
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

    public function render($request, Throwable $exception)
    {
        // return parent::render($request, $exception);
        Log::error('Exception Handler', [
            $exception->getMessage()
        ]);

        if (str_contains($request->url(), 'localhost/')) {
            return parent::render($request, $exception);
        }
        

        if (str_contains($request->url(), '/api/')) {
            return response()->json([
                'status' => 500,
                'response' => null,
                'error' => $exception->getMessage()
            ]);
        }

        if ($exception instanceof TokenMismatchException) {
            if (str_contains($request->url(), '/admin/')) {
                return redirect('admin/login');
            } else {
                return redirect('/');
            }
        } else if ($exception instanceof NotFoundHttpException) {
            return response()->view('error.error_404', [], 404);
        } else {
            return response()->view('error.error_500', [], 500);

            // return redirect('error/500');
        }

        return parent::render($request, $exception);
    }
    public function register()
    {
        $this->reportable(function (Throwable $e) {
        });
    }
}
