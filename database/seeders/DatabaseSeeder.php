<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

//        $this->call([
//            RoleAndPermissionSeeder::class,
//        ]);

        $admin = new User([
            'id' => '1' ,
            'name' => 'Admin',
            'email' => 'admin123@test.com',
            'email_verified_at' => '2025-01-01',
            'phone' => '9809876543',
            'password' => bcrypt('12345678'),
        ]);
        $admin->save();
    }
}
