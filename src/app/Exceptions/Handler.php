<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * Return error in json format
     *
     * @param  mixed $request
     * @param  mixed $exception
     * @return void
     */
    public function render($request, Exception $exception)
    {
        //HTTP Error Code 405
        if ($exception instanceof MethodNotAllowedHttpException) {
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $exception = new MethodNotAllowedHttpException(
                [],
                'HTTP_METHOD_NOT_ALLOWED',
                $exception
            );
        }
        //HTTP Error Code 404
        elseif ($exception instanceof NotFoundHttpException) {
            $status = Response::HTTP_NOT_FOUND;
            $exception = new NotFoundHttpException('HTTP_NOT_FOUND', $exception);
        }
        //HTTP Error Code 403
        elseif ($exception instanceof AuthorizationException) {
            $status = Response::HTTP_FORBIDDEN;
            $exception = new    AuthorizationException('HTTP_FORBIDDEN', $status);
        } else {
            $status = $exception->getCode() ? $exception->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        }


        return response()->json(
            [
                "error" => 'ERROR',
                'code' =>  $status,
                'message' => $exception->getMessage()
            ],
            $status
        );
    }
}
