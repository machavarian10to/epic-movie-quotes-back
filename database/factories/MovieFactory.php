<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 *
	 * @throws \JsonException
	 */
	public function definition()
	{
		return [
			'user_id'              => 1,
			'name'                 => json_decode(json_encode(['en' => fake()->userName, 'ka' => fake()->userName], JSON_THROW_ON_ERROR)),
			'director'             => json_decode(json_encode(['en' => fake()->name, 'ka' => fake()->name], JSON_THROW_ON_ERROR)),
			'description'          => json_decode(json_encode(['en' => fake()->paragraph, 'ka' => fake()->paragraph], JSON_THROW_ON_ERROR)),
			'budget'               => fake()->randomNumber(2),
			'year'                 => fake()->randomNumber(4, true),
			'image'                => '/images/1668504250_siuu.jpg',
		];
	}
}
