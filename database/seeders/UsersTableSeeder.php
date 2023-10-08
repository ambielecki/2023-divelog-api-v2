<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $user = new \App\Models\User();
            $user->first_name = 'Andrew';
            $user->last_name = 'Bielecki';
            $user->email = 'ambielecki@gmail.com';
            $user->password = \Illuminate\Support\Facades\Hash::make(config('app.default_password'));
            $user->is_admin = 1;
            $user->save();

            $user = new \App\Models\User();
            $user->first_name = 'Testy';
            $user->last_name = 'McTersterson';
            $user->email = config('app.test_user_email');
            $user->password = \Illuminate\Support\Facades\Hash::make(config('app.test_user_password'));
            $user->is_admin = 0;
            $user->save();
        }
    }
}
