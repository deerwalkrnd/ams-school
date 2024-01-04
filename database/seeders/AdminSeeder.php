<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new User();
        $admin->name = "Admin";
        $admin->email = "admin@sifal.deerwalk.edu.np";
        $admin->password= bcrypt("admin");
        $admin->save(); 
        $admin->roles()->attach('1');

        $teacher = new User();
        $teacher->name = "Test";
        $teacher->email = "test@deerwalk.edu.np";
        $teacher->password = bcrypt("teacher");
        $teacher->save();
        $teacher->roles()->attach('2');

    }
}
