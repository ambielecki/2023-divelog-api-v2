<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {
    protected $fillable = ['name', 'options', 'tries', 'status'];

    const STATUS_PENDING = 'pending';
    const STATUS_RUNNING = 'running';
    const STATUS_COMPLETE = 'complete';
    const STATUS_FAILED = 'failed';
    const STATUS_ABANDONED = 'abandoned';

    const TASK_IMAGE_RESIZE = 'image_resize';

    public function getOptionsAttribute($value): array {
        return json_decode($value, true);
    }

    public function setOptionsAttribute($value): void {
        $this->attributes['options'] = json_encode($value ?: []);
    }
}
