<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($msg, $code)
    {
        return response()->json(['error' => $msg, 'code' => $code], $code);
    }

    protected static function errorResponseS($msg, $code)
    {
        return response()->json(['error' => $msg, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        return $this->successResponse(['data' => $collection, 'code' => $code], $code);
    }

    protected function showAllPaginate(LengthAwarePaginator $collection, $code = 200)
    {
        return $this->successResponse(['data' => $collection, 'code' => $code], $code);
    }

    protected function showOne(Model $model, $code = 200)
    {
        return $this->successResponse(['data' => $model, 'code' => $code], $code);
    }

    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }

    protected function showAllTransformer(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()) return $this->successResponse(['data' => $collection, 'code' => $code], $code);
        $transformer = $collection->first()->transformer;
        $collection = $this->transformData($collection, $transformer);
        return $this->successResponse([$collection, 'code' => $code], $code);
    }

    protected function showOneTransformer(Model $model, $code = 200)
    {
        $transformer = $model->transformer;
        $model = $this->transformData($model, $transformer);
        return $this->successResponse([$model, 'code' => $code], $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['message' => $message, 'code' => $code], $code);
    }
}
