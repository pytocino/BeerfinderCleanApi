<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponser
{
    /**
     * Respuesta exitosa.
     */
    protected function successResponse($data, string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Respuesta de error.
     */
    protected function errorResponse(string $message, int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null
        ], $code);
    }

    /**
     * Mostrar todos los elementos de una colección como recurso.
     */
    protected function showAll($collection, int $code = Response::HTTP_OK): JsonResponse
    {
        if ($collection instanceof LengthAwarePaginator) {
            $collection = $collection->getCollection();
        }

        return $this->successResponse($collection, '', $code);
    }

    /**
     * Mostrar un elemento específico como recurso.
     */
    protected function showOne($instance, int $code = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse($instance, '', $code);
    }
}
