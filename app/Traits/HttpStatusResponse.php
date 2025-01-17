<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait HttpStatusResponse
{
    public function success(
        ?string $message = 'Request successful.',
        array|Collection|JsonResource|Model | LengthAwarePaginator $data = [],
        array $meta = [],
        array $stat = [],
        int $code = 200
    ): JsonResponse  {
        $res['message'] = $message;
        $res['data'] = $data;
        if ($meta) {
            $res['meta'] = $meta;
        }
        if ($stat) {
            $res['stat'] = $stat;
        }

        return response()->json($res, $code);
    }

    public function failure(?string $message = 'Request failed.', string $errors = 'Request failed', int $code = 400): JsonResponse
    {
        $res['message'] = $message;
        if ($errors) {
            $res['errors'] = $errors;
        }

        return response()->json($res, $code);
    }
}
