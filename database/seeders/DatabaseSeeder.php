<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\PostFactory;
use App\Models\Entities\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
        ]);


        // PostFactory::new()->count(10)->create();
        Post::factory(10)->create();

        Post::factory()->create([
            'title' => 'Test Post',
            'content' => 'This is a test post.',
        ]);
    }
}
