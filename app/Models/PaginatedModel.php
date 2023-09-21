<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class PaginatedModel extends Model {
    const DEFAULT_SORT_FIELD = 'created_at';
    const DEFAULT_SORT_DIRECTION = 'DESC';

    protected array $allowed_sorts = [
        'created_at' => 1,
    ];

    protected array $allowed_sort_directions = [
        'ASC'  => 1,
        'asc'  => 1,
        'DESC' => 1,
        'desc' => 1,
    ];

    public function getPaginatedResults(Request $request): array {
        $page = (int) $request->input('page') ?: 1;
        $limit = (int) $request->input('limit') ?: 20;

        $skip = ($page - 1) * $limit;
        $sort = self::DEFAULT_SORT_FIELD;
        $sort_direction = self::DEFAULT_SORT_DIRECTION;

        if ($request->input('sort') && isset($this->allowed_sorts[$request->input('sort')])) {
            $sort = $request->input('sort');
        }

        if ($request->input('sort_direction') && isset($this->allowed_sort_directions[$request->input('sort_direction')])) {
            $sort_direction = strtoupper($request->input('sort_direction'));
        }

        $query = self::query();
        $query = $this->addUser($query, $request);

        $count = $query->count();

        $results = $query
            ->orderBy($sort, $sort_direction)
            ->limit($limit)
            ->skip($skip)
            ->get();

        $class = Str::snake(Str::plural((new \ReflectionClass($this))->getShortName()));

        return [
            $class => $results,
            'page'           => $page,
            'pages'          => ceil($count / $limit),
            'limit'          => $limit,
        ];
    }

    protected function addUser(Builder $query, Request $request): Builder {
        return $query;
    }
}
