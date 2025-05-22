<?php

namespace App\Helpers;

use App\Traits\ApiResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandlerHelper
{
    use ApiResponse;

    /**
     * Handle Validation Exception
     */
    public function handleValidationException(ValidationException $e, $request)
    {
        return $this->errorResponse(
            'Validation failed',
            $e->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Handle NotFoundException
     */
    public function handleNotFoundHttpException(NotFoundHttpException $e)
    {
        return $this->errorResponse(
            'The requested resource or route could not be found',
            code: Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Handle HttpException
     */
    public function handleHttpException(HttpException $e)
    {
        return $this->errorResponse(
            $e->getMessage(),
            null,
            $e->getStatusCode()
        );
    }
}
