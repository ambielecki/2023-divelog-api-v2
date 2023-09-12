<?php

namespace Database\Seeders;

use App\Models\DiveLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiveLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DiveLog::factory()->count(30)->create(['user_id' => 1]);
    }
}
