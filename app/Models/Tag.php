<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

class Tag extends Model {
    use HasFactory;

    protected $fillable = ['name',];

    public function setNameAttribute($value): void {
        $this->attributes['name'] = strtolower($value);
    }

    public function images(): BelongsToMany {
        return $this->belongsToMany(Image::class)->withTimestamps();
    }

    public function getList(Request $request): array {
        $query = self::query()
            ->orderBy('name');

        if ($request->input('search')) {
            $search = $request->input('search');
            $query = $query->where('name', 'LIKE', "%$search%");
        }

        return $query->get()->toArray();
    }

    public static function getListOfIds(array $tag_names): array {
        $tag_ids = [];
        foreach ($tag_names as $name) {
            $tag = self::where('name', $name)->first();
            if (!$tag) {
                $tag = new self();
                $tag->name = $name;
                $tag->save();
            }

            $tag_ids[] = $tag->id;
        }

        return $tag_ids;
    }
}
