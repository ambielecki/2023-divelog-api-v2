<?php

namespace App\Models\Pages;

use App\Library\ShortTags;
use App\Models\Image;
use App\Models\Page;
use App\Scopes\BlogPageScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class BlogPage extends Page {
    use HasFactory;

    const PAGE_TYPE = 'blog';
    const DEFAULT_SORT_FIELD = 'parent_id';
    const DEFAULT_SORT_DIRECTION = 'DESC';
    const DEFAULT_LIMIT = 10;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);

        $this->allowed_sorts[self::DEFAULT_SORT_FIELD] = 1;
    }

    protected static function boot(): void {
        parent::boot();

        static::addGlobalScope(new BlogPageScope());

        static::creating(function ($query) {
            $query->page_type = self::PAGE_TYPE;
        });
    }

    public function getContentAttribute($value): array {
        $content = json_decode($value, true);

        $blog_content = $content['content'];
        $content['images'] = [];
        preg_match_all(ShortTags::IMAGE_REGEX, $blog_content, $image_matches);
        preg_match(static::PARAGRAPH_REGEX, $blog_content, $paragraph_match);
        if (isset($image_matches[1])) {
            $images = Image::whereIn('id', $image_matches[1])->get();
            foreach ($image_matches[0] as $key => $to_replace) {
                $image_id = (int) $image_matches[1][$key];
                $image = $images->first(fn ($image) => $image_id === $image->id);
                $replacement = $this->getImageHtml($image);
                $blog_content = str_replace($to_replace, $replacement, $blog_content);
            }
            $content['images'] = $images;
        }

        $content['first_paragraph'] = $paragraph_match[0] ?? '';
        $content['content_with_images'] = $blog_content;

        return $content;
    }

    public static function createBlogEntry(array $request_data): self {
        $slug = self::createSlug($request_data['page']['content']['title']);

        $blog = new self();
        $blog->revision = 1;
        $blog->is_active = 1;
        $blog->is_published = 1;
        $blog->title = $request_data['page']['content']['title'];
        $blog->slug = $slug;
        $blog->content = $request_data['page']['content'];
        $blog->save();
        $blog->parent_id = $blog->id;
        $blog->save();

        return $blog;
    }

    public static function createSlug(string $title): string {
        $base_slug = strtolower(str_replace(' ', '-', $title));
        $check = self::query()
            ->where('slug', $base_slug)
            ->first();

        if (!$check) {
            return $base_slug;
        }

        $number = 2;
        while ($check) {
            $new_slug = strtolower(str_replace(' ', '-', $title)) . '-' . (string) $number;
            $check = self::query()
                ->where('slug', $new_slug)
                ->first();

            $number++;
        }

        return $new_slug;
    }

    private function getImageHtml($image) {
        $path = config('app.url');
        if ($image->has_sizes) {
            $src = $path . "$image->public_path/medium/$image->file_name";
        } else {
            $src = $path . "$image->public_path/$image->file_name";
        }

        return "<img src='$src' alt='$image->alt_tag'>";
    }

    public function addWheres(Builder $query, Request $request): Builder {
        if ($request->path() === 'api/admin/blog') {
            return $query->where([
                ['is_active', 1],
            ]);
        }
        return $query->where([
            ['is_active', 1],
            ['is_published', 1]
        ]);
    }
}
