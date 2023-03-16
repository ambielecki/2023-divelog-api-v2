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
        $max_dive = DiveLog::where('user_id', 1)->max('dive_number');

        $dive_log = new DiveLog();
        $dive_log->user_id = 1;
        $dive_log->dive_number = $max_dive + 1;
        $dive_log->location = 'Test Location';
        $dive_log->dive_site = 'Test Site';
        $dive_log->description = 'Some text here';
        $dive_log->dive_details = json_encode((object) []);
        $dive_log->equipment_details = json_encode((object) []);

        $dive_log->save();
    }
}
