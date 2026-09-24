<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
                'mobile' => '0000000000',
                'utype' => 'adm',
            ]
        );

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'utype' => 'usr',
        ]);

        Category::updateOrCreate(
            ['slug' => 'category-1'],
            [
                'name' => 'Category 1',
                'status' => 'active',
            ]
        );
    }
}
