<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = ['jamaica', 'dolphin', 'shark', 'bahamas', 'bonaire', 'bermuda', 'wreck', 'grouper', 'parrot fish', 'beaches', 'boat'];
        foreach ($tags as $name) {
            $tag = new Tag();
            $tag->name = $name;
            $tag->save();
        }
    }
}
