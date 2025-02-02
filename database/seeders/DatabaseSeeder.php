<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        $this->call([
//            RoleAndPermissionSeeder::class,
//        ]);

        $admin = new User([
            'id' => '1',
            'name' => 'Admin',
            'email' => 'admin123@test.com',
            'email_verified_at' => '2025-01-01',
            'phone' => '9809876543',
            'password' => bcrypt('12345678'),
        ]);
        $admin->save();

        $user = new User([
            'name' => 'User1',
            'email' => 'user@test.com',
            'phone' => '9809876542',
            'password' => bcrypt('12345678'),
        ]);
        $user->save();

        $vendor = new Vendor([
            'name' => 'Vendor1',
            'email' => 'vendor@test.com',
            'phone' => '9809876541',
            'password' => bcrypt('12345678'),
        ]);
        $vendor->save();
    }
}
