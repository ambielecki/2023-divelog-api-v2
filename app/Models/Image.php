<?php

namespace App\Models;

use App\Http\Requests\Image\ImageCreateRequest;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Intervention\Image\Facades\Image as InverventionImage;
use Illuminate\Support\Facades\Log;

class Image extends PaginatedModel {
    protected $appends = ['public_path'];

    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class)
            ->withTimestamps()
            ->orderBy('name', 'ASC');
    }

    public function publicPath(): Attribute {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => str_replace('/app/public', '/storage', $attributes['folder'])
        );
    }

    public static function createImage(ImageCreateRequest $request): ?self {
        try {
            $image = InverventionImage::make($request->file('image_file'))->encode('jpg');
            $width = $image->width();
            if ($width > 3840) {
                $image->resize(3840, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $folder = '/app/public/images/' . date('Y-m');

            $unique_check = false;
            while (!$unique_check) {
                $file_name = uniqid(date('Y-m-d') . '_', false);
                if (!Image::where('file_name', $file_name . '.jpg')->count()) {
                    $unique_check = true;
                }
            }

            $path = $folder . '/' . $file_name;

            // check if folder exists, if not create it
            if (!File::exists(storage_path('/app/public/images/'))) {
                File::makeDirectory(storage_path('/app/public/images'));
            }

            if (!File::exists(storage_path($folder))) {
                File::makeDirectory(storage_path($folder));
            }

            if ($image->save(storage_path($path . '.jpg'))) {
                $db_image = new self();
                $db_image->file_name = $file_name . '.jpg';
                $db_image->folder = $folder;
                $db_image->alt_tag = $request->input('alt_tag');
                $db_image->description = $request->input('description');
                $db_image->is_hero = $request->input('is_hero') ? 1 : 0;
                $db_image->save();

                $task = Task::create([
                    'name'    => Task::TASK_IMAGE_RESIZE,
                    'options' => ['image_id' => $db_image->id],
                    'tries'   => 0,
                    'status'  => Task::STATUS_PENDING,
                ]);

                $path = base_path();

                // TODO: Convert this to a job / queue
                shell_exec("cd $path && nohup php artisan divelog:resize_image $task->id >> /dev/null 2>&1 &");

                return $db_image;
            }

            return null;
        } catch (Exception $e) {
            Log::error($e);

            return null;
        }
    }

    protected function addRelations(Builder $query): Builder {
        return $query->with('tags');
    }
}
