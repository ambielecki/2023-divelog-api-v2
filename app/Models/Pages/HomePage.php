<?php

namespace App\Models\Pages;

use App\Models\Image;
use App\Models\Page;
use App\Scopes\HomePageScope;

class HomePage extends Page {
    const PAGE_TYPE = 'home';

    protected static function boot(): void {
        parent::boot();

        static::addGlobalScope(new HomePageScope());

        static::creating(function ($query) {
            $query->page_type = self::PAGE_TYPE;
            $query->slug = '/';
        });
    }

    public function getContentAttribute($value): array {
        $content = json_decode($value, true);

        if (isset($content['hero_image'])) {
            $hero_image = Image::find($content['hero_image']['id']);
            $hero_image = $hero_image ? $hero_image->toArray() : [];

            $content['hero_image'] = array_merge($hero_image, $content['hero_image']);
        }

        if (isset($content['carousel_images']['ids'])) {
            $ids = explode(',', $content['carousel_images']['ids']);
            $carousel_images = Image::query()
                ->whereIn('id', $ids)
                ->get()
                ->toArray();

            $content['carousel_images']['images'] = $carousel_images;
        }

        return $content;
    }
}
