<?php

namespace App\Scopes;

use App\Models\Pages\BlogPage;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BlogPageScope implements Scope {
    public function apply(Builder $builder, Model $model): void {
        $builder->where('page_type', BlogPage::PAGE_TYPE);
    }
}
