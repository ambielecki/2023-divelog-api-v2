<?php

namespace App\Models;

use App\Http\Requests\DiveLog\DiveLogIndexRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiveLog extends Model
{
    use HasFactory;

    const DIVE_NUMBER = 'dive_number';
    const SORTABLE_FIELDS = [
        self::DIVE_NUMBER,
    ];

    public function getLogs(DiveLogIndexRequest $request): array {
        $page = (int) $request->input('page') ?? 1;
        $limit = (int) $request->input('limit') ?? 20;

        $skip = ($page - 1) * $limit;

        $sort = $request->input('sort');
        $sort_direction = $request->input('sort_direction') ?? 'dive_number';

        $user = $request->user();

        $query = DiveLog::query()
            ->where('user_id', $user->id);

        $count = $query->count('id');

        $logs = $query
            ->orderBy($sort, $sort_direction)
            ->limit($limit)
            ->skip($skip)
            ->get();

        return [
            'dive_logs' => $logs,
            'page' => $page,
            'pages' => ceil($count / $limit),
            'limit' => $limit,
        ];
    }
}
