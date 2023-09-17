<?php

namespace App\Models;

use App\Http\Requests\DiveLog\DiveLogIndexRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DiveLog extends Model {
    use HasFactory;

    const DIVE_NUMBER = 'dive_number';
    const SORTABLE_FIELDS = [
        self::DIVE_NUMBER,
    ];

    protected $casts = [
        'dive_details'      => 'array',
        'equipment_details' => 'array',
    ];

    public function getLogs(DiveLogIndexRequest $request): array {
        $page = (int)$request->input('page') ?? 1;
        $limit = (int)$request->input('limit') ?? 20;

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
            'page'      => $page,
            'pages'     => ceil($count / $limit),
            'limit'     => $limit,
        ];
    }

    public function fillLog(Request $request, bool $new = false) {
        if ($new) {
            $this->user_id = $request->user()->id;
        }

        $this->dive_number = $request->input('dive_number');
        $this->location = $request->input('location');
        $this->dive_site = $request->input('dive_site');
        $this->buddy = $request->input('buddy');
        $this->date_time = $request->input('date_time');
        $this->max_depth_ft = $request->input('max_depth_ft');
        $this->bottom_time_min = $request->input('bottom_time_min');
        $this->surface_interval_min = $request->input('surface_interval_min');
        $this->used_computer = $request->input('used_computer');
        $this->description = $request->input('description');
        $this->notes = $request->input('notes');
        $this->dive_details = [];
        $this->equipment_details = [];
    }
}
