<?php

namespace App\Traits;

use App\Exceptions\customFormValidationException;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

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
        $this->filterData($collection);
        $this->sortData($collection);
        $this->paginate($collection);
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

    protected function sortData(Collection &$collection)
    {
        if (request()->has('sort_by')) {
            $attribute = request()->sort_by;
            $isDesc = request()->has('desc');
            $collection = $collection->sortBy($attribute, null, $isDesc)->values();
        }
        return $collection;
    }

    protected function filterData(Collection &$collection)
    {
        $allowedAtt = User::getAttributesArray((new User())->find(1));
        foreach (request()->query() as $att => $val) {
            if (key_exists($att, $allowedAtt) && isset($val)) {
                $collection = $collection->where($att, $val)->values();
            }
        }
        return $collection;
    }

    protected function paginate(Collection &$collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails())
            throw new customFormValidationException($validator, response()); # here throwing an exception MUST be used instead of just returning

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        if (request()->has('per_page'))
            $perPage = request()->per_page;
        $result = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($result, $collection->count(), $perPage, $page, [
            'path' => url()->current()
        ]);
        //$paginated->appends(request()->all());
        $collection = $paginated;
        return $paginated;
    }

    protected function cacheResponse(Collection $collection)
    {
        $url = request()->url();
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 30, function () use ($collection) {
            return $collection;
        });
    }
}

