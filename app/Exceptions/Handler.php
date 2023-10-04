<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Fruitcake\Cors\CorsService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        #another way
        if (!$this->isWebBased($request)) {
            //if ($e instanceof customFormValidationException)
            //return customFormValidationException::customValidationException($e, $request);
            if ($e instanceof ValidationException)
                return $this->customValidationException($e, $request);
            if ($e instanceof ModelNotFoundException) {
                $modelName = class_basename($e->getModel()); // App\\Models\\User --> User
                return $this->errorResponse("Model {$modelName} Not Found", 404); // 404: not  found
            }
            if ($e instanceof AuthenticationException)
                return $this->errorResponse('Unauthenticated User', 401);// 401: Unauthenticated
            if ($e instanceof AuthorizationException)
                return $this->errorResponse($e->getMessage(), 403);// 403: Unauthorized
            if ($e instanceof NotFoundHttpException)
                return $this->errorResponse("This Endpoint Does not Exist", 404);// 404: not  found
            if ($e instanceof MethodNotAllowedHttpException)
                return $this->errorResponse("This HTTP Method Is Not Allowed", 405);// 405: Method Not Found
            if ($e instanceof HttpException)
                return $this->errorResponse($e->getMessage(), $e->getStatusCode());
            if ($e instanceof QueryException) {
                $errorCode = $e->errorInfo[1];
                $errorMsg = $e->errorInfo[2]; // danger: it is a bad practice to include error msg of MySQL as it is a security thread
                if ($errorCode == 1451)
                    return $this->errorResponse('Cannot delete this resource as it is related to another resource', 409);
                return $this->errorResponse(['DBcode' => $errorCode, 'DBmsg' => $errorMsg], 409);
            }
            if ($e instanceof TokenMismatchException)
                return redirect()->back()->withInput($request->input());

            if (!config('app.debug')) # if debug mode is off (we are now in production)
                return $this->errorResponse('Unexpected Exception. Try Later', 500); // 500: server error

            return parent::render($request, $e);
        }
        return parent::render($request, $e); // TODO: Change the autogenerated stub
    }

    public function customValidationException(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        return $this->errorResponse($errors, 422);
    }

    # to validate that it is a request from web not an API
    private function isWebBased($request)
    {
        //return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
        return $request->acceptsHtml();
    }
}

