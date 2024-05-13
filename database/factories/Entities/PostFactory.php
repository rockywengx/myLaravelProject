<?php

namespace Database\Factories\Entities;

use App\Models\Entities\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entities>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     //規範假資料的形式
    public function definition(): array
    {
        return [
            //
            'title' => $this->faker->sentence(),
            'content' => $this->faker->text(255),
        ];
    }
}
