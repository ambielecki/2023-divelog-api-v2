<?php

namespace App\Scopes;

use App\Models\Pages\HomePage;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class HomePageScope implements Scope {
    public function apply(Builder $builder, Model $model): void {
        $builder->where('page_type', HomePage::PAGE_TYPE);
    }
}
