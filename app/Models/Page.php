<?php

namespace App\Models;

class Page extends PaginatedModel {
    const PARAGRAPH_REGEX = '/<p>(.*?)<\/p>/m';

    protected $table = 'pages';
    protected $fillable = ['page_type', 'slug', 'title', 'content', 'is_active', 'revision', 'parent_id'];

    const PAGE_TYPE = 'page';

    public function setContentAttribute($value): void {
        $this->attributes['content'] = json_encode($value ?: []);
    }
}
