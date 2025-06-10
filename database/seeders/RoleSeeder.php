<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'teacher'];
        foreach ($roles as $role) {
            $newRole = new Role;
            $newRole->role = $role;
            $newRole->save();
        }
    }
}
