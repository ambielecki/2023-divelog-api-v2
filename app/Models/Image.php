<?php

namespace App\Models;

use App\Http\Requests\ImageCreateRequest;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Intervention\Image\Facades\Image as InverventionImage;
use Illuminate\Support\Facades\Log;

class Image extends Model {
    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class)
            ->withTimestamps()
            ->orderBy('name', 'ASC');
    }

    public static function createImage(ImageCreateRequest $request): int {
        try {
            $image = InverventionImage::make($request->file('image_file'))->encode('jpg');
            $width = $image->width();
            if ($width > 3840) {
                $image->resize(3840, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $folder = 'images/' . date('Y-m') . '/';

            $unique_check = false;
            while (!$unique_check) {
                $file_name = uniqid(date('Y-m-d') . '_', false);
                if (!Image::where('file_name', $file_name . '.jpg')->count()) {
                    $unique_check = true;
                }
            }

            $path = $folder . $file_name;

            // check if folder exists, if not create it
            if (!File::exists(public_path('images/'))) {
                File::makeDirectory(public_path('images'));
            }

            if (!File::exists(public_path($folder))) {
                File::makeDirectory(public_path($folder));
            }

            if ($image->save(public_path($path . '.jpg'))) {
                $db_image = new self();
                $db_image->file_name = $file_name . '.jpg';
                $db_image->folder = $folder;
                $db_image->title = $request->input('title');
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
                shell_exec("cd $path && nohup php artisan divelog:resize_image $task->id >> /dev/null 2>&1 &");

                return $db_image->id;
            }

            return 0;
        } catch (Exception $e) {
            Log::error($e);

            return 0;
        }
    }
}
