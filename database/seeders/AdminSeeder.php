<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'dssattendance@deerwalk.edu.np ';
        $admin->password = bcrypt('jQDC9&>ndyeA7nQ>');
        $admin->save();
        $admin->roles()->attach('1');

        $teacher = new User();
        $teacher->name = 'Test';
        $teacher->email = 'test@deerwalk.edu.np';
        $teacher->password = bcrypt('teacher');
        $teacher->save();
        $teacher->roles()->attach('2');

    }
}
