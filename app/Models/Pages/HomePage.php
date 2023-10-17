<?php

namespace App\Models\Pages;

use App\Models\Image;
use App\Models\Page;
use App\Scopes\HomePageScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class HomePage extends Page {
    use HasFactory;

    const PAGE_TYPE = 'home';
    const TITLE = 'Dive Log Repeat - Home';

    protected static function boot(): void {
        parent::boot();

        static::addGlobalScope(new HomePageScope());

        static::creating(function ($query) {
            $query->page_type = self::PAGE_TYPE;
            $query->title = self::TITLE;
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

    public static function setHomePage(array $request_data): self {
        $data = [
            'parent_id' => null,
            'revision'  => 1,
        ];

        $first_home_page = HomePage::query()
            ->orderBy('revision', 'ASC')
            ->first();

        if ($first_home_page) {
            $last_revision = (int) HomePage::query()->max('revision');
            $data = [
                'parent_id' => $first_home_page->id,
                'revision'  => $last_revision + 1,
            ];
        }

        DB::beginTransaction();

        HomePage::query()->update([
            'is_active'    => 0,
            'is_published' => 0,
        ]);

        $home_page = self::create(array_merge([
            'is_active'    => true,
            'is_published' => true,
            'content'      => [
                'content'           => $request_data['page']['content']['content'],
                'image_description' => $request_data['page']['content']['image_description'] ?? '',
                'title'             => $request_data['page']['content']['title'],
                'hero_image'        => $request_data['hero_image'] ?? null,
                'carousel_images'   => $request_data['carousel_images'] ?? [],
            ],
        ], $data));

        DB::commit();

        return $home_page;
    }
}
