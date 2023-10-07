<?php

namespace App\Models;

use App\Http\Requests\DiveLog\DiveLogIndexRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class DiveLog extends PaginatedModel {
    use HasFactory;

    const DEFAULT_SORT_FIELD  = 'dive_number';

    protected $casts = [
        'dive_details'      => 'array',
        'equipment_details' => 'array',
    ];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);

        $this->allowed_sorts[self::DEFAULT_SORT_FIELD] = 1;
    }

    protected function addUser(Builder $query, Request $request): Builder {
        return $query->where('user_id', $request->user()->id);
    }

    public function fillLog(Request $request, bool $new = false) {
        if ($new) {
            $this->user_id = $request->user()->id;
        }

        $this->dive_number = $request->input('dive_number');
        $this->location = $request->input('location');
        $this->dive_site = $request->input('dive_site');
        $this->buddy = $request->input('buddy');
        $this->date_time = date('Y-m-d H:i:s', strtotime($request->input('date_time')));
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
