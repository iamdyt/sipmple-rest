<?php

namespace App\Exceptions;

use App\Traits\HttpStatusResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use UnexpectedValueException;
use UnhandledMatchError;

class Handler extends ExceptionHandler
{
    
    use HttpStatusResponse;
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
        $this->reportable(function (CustomException $e) {
        })->stop();

        $this->reportable(function (Throwable $e) {
            //
        });
    }
    
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            if ($e instanceof ConnectionException) {
                return $this->failure('Connection error', code: 400);
            }

            if ($e instanceof CustomException) {
                return $this->failure($e->getMessage() ?? 'An error occurred!', code: 400);
            }

            if ($e instanceof NotFoundHttpException) {
                return $this->failure('Invalid route!', code: 404);
            }

            if ($e instanceof ModelNotFoundException) {
                return $this->failure('Model not found!', code: 404);
            }

            if ($e instanceof MethodNotAllowedException) {
                return $this->failure(message: $e->getMessage(), code: 405);
            }

            if ($e instanceof ThrottleRequests) {
                return $this->failure('Too many requests!', code: 429);
            }

            if ($e instanceof AuthorizationException) {
                return $this->failure($e->getMessage() ?? 'You don\'t own this resource!', code: 403);
            }

            if ($e instanceof PostTooLargeException) {
                return $this->failure('Payload too large!', code: 413);
            }

            if ($e instanceof HttpException) {
                return $this->failure(
                    message: $e->getMessage() ?? 'You don\'t have the right permissions to access this resource', 
                    code: 403
                );
            }

            if ($e instanceof ValidationException) {
                return $this->failure('Invalid payload!', array_values($e->errors())[0][0], 422);
            }

            if ($e instanceof RouteNotFoundException) {
                return $this->failure('Route not found');
            }

            if ($e instanceof UnhandledMatchError) {
                logger($e->getMessage());

                return $this->failure(message: 'Invalid type ', code: 422);
            }
            if ($e instanceof AuthenticationException) {

                return $this->failure(message: 'Unauthenticated, Please re-login', code: 422);
            }
            
            logger('Uncaught exception due to '.$e->getMessage());
            logger('Exception trace '.$e);

            return $this->failure('An error occured, try again in few moments');
        }

        return parent::render($request, $e);
    }
}
