<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Buat role
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $accountant = Role::firstOrCreate(['name' => 'accountant']);
        $viewer = Role::firstOrCreate(['name' => 'viewer']);

        // Buat user admin default
        $user = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true
            ]
        );
        $user->assignRole($admin);
    }
}
