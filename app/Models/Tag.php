<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model {
    protected $fillable = ['name',];

    public function setNameAttribute($value): void {
        $this->attributes['name'] = strtolower($value);
    }

    public function images(): BelongsToMany {
        return $this->belongsToMany(Image::class)->withTimestamps();
    }

    public static function createNewTags(string $tags): array {
        $ids = [];
        $tag_names = explode(',', str_replace(' ', '', $tags));

        foreach ($tag_names as $tag_name) {
            if ($tag_name) {
                $tag = Tag::where('name', $tag_name)->first();
                if (!$tag) {
                    $tag = self::create(['name' => strtolower($tag_name)]);
                }

                $ids[] = $tag->id;
            }
        }

        return $ids;
    }
}
