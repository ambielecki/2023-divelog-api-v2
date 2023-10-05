<?php

namespace App\Models\Pages;

use App\Models\Image;
use App\Models\Page;
use App\Scopes\HomePageScope;
use Illuminate\Http\Request;

class HomePage extends Page {
    const PAGE_TYPE = 'home';
    const TITLE = 'Dive Log Repeat - Home';

    protected static function boot(): void {
        parent::boot();

        static::addGlobalScope(new HomePageScope());

        static::creating(function ($query) {
            $query->page_type = self::PAGE_TYPE;
            $query->title =
            $query->slug = '/';
        });
    }

    public function getContentAttribute($value): array {
        $content = json_decode($value, true);

        if (isset($content['hero_image'])) {
            $hero_image = Image::find($content['hero_image']);
            $hero_image = $hero_image ? $hero_image->toArray() : [];

            $content['hero_image'] = $hero_image;
        }

        if (isset($content['carousel_images'])) {
            $carousel_images = Image::query()
                ->whereIn('id', $content['carousel_images'])
                ->get()
                ->toArray();

            $content['carousel_images'] = $carousel_images;
        }

        return $content;
    }
}
