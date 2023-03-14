<?php

namespace App\Console\Commands;

use App\Console\Traits\UsesDatedConsoleOutput;
use App\Models\Image;
use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Intervention\Image\Image as InterventionImage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Log;

class ResizeImageCommand extends Command {
    use UsesDatedConsoleOutput;

    protected $signature = 'divelog:resize_image
        {id : the task id}';

    protected $description = 'Stores various image sizes after upload';

    public function handle(): void {
        $task = Task::find($this->argument('id'));

        if (!$task || !isset($task->options['image_id'])) {
            $task->status = Task::STATUS_ABANDONED;
            $task->save();
            $this->line('Image could not be found, task marked as abandoned');

            return;
        }

        $task->status = Task::STATUS_RUNNING;
        $task->tries++;
        $task->save();

        $image_record = Image::find($task->options['image_id']);
        $this->line("Found image id: $image_record->id");

        $manager = new ImageManager(['driver' => 'gd',]);
        $image = $manager->make(File::get(public_path($image_record->folder . $image_record->file_name)));

        try {
            $image->backup();
            if ($image_record->has_high_res) {
                $this->createImage($image, '3840', $image_record->folder . 'xl/', $image_record->file_name);
            }

            $this->createImage($image, '1920', $image_record->folder . 'large/', $image_record->file_name);
            $this->createImage($image, '1080', $image_record->folder . 'medium/', $image_record->file_name);
            $this->createImage($image, '600', $image_record->folder . 'small/', $image_record->file_name);

            $image_record->has_sizes = 1;
            $image_record->save();

            $task->status = Task::STATUS_COMPLETE;
        } catch (Exception $exception) {
            Log::error($exception);
            $task->status = Task::STATUS_FAILED;
        }

        $task->save();
    }

    private function createImage(InterventionImage $image, string $width, string $folder, string $file_name): void {
        if (!File::exists(public_path($folder))) {
            File::makeDirectory(public_path($folder));
        }

        $image->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $image->save(public_path($folder . $file_name), 70);
        $image->reset();
    }
}
