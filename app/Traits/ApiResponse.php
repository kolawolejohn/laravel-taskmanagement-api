<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data = null, $message = null, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string|null $message
     * @param int $code
     * @param mixed $data
     * @return JsonResponse
     */
    protected function errorResponse($message = null, $data = null, $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $data,
        ], $code);
    }


    protected function showAll(Collection $collection, $message = null, $code = 200)
    {
        return $this->successResponse($collection, $message, $code);
    }

    protected function showAllPaginated(LengthAwarePaginator $paginator, $message  = null, $code = 200)
    {
        return $this->successResponse([
            'items' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),

            ],
        ], $message, $code);
    }

    protected function showOne(Model $model, $message = null, $code = 200)
    {
        return $this->successResponse($model, $message, $code);
    }
}
