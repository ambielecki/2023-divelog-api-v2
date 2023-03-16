<?php

namespace App\Models;

use App\Library\JsonResponseData;
use App\Library\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class ApiModel extends Model {
    protected function addSearch(Builder $query): Builder {
        return $query;
    }

    protected function addAuthorization(Builder $query): Builder {
        return $query;
    }

    public function getResponse(Request $request, Builder $query): JsonResponse {
        $limit = (int) ($request->input('limit') ?? '25');
        $page = (int) ($request->input('page') ?? '1');
        $skip = ($page - 1) * $limit;

        $query = $this->addAuthorization($query);

        $query = $this->addSearch($query);


        // TODO: Implement check of sortable fields
        if ($request->input('sort')) {
            $query = $query->orderBy(
                $request->input('sort'),
                $request->input('sort_direction') ?? 'ASC'
            );
        }

        $count = $query->count();

        $items = $query
            ->skip($skip)
            ->limit($limit)
            ->get();

        $class_name = Str::plural(Str::snake((new \ReflectionClass($this))->getShortName()));

        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            [
                $class_name => $items,
                'count' => $count,
                'limit' => $limit,
                'page' => $page,
                'pages' => ceil($count / $limit),
            ]
        ));
    }
}
