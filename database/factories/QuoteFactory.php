<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
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
			'movie_id'             => 1,
			'user_id'              => 1,
			'name'                 => json_decode(json_encode(['en' => fake()->text, 'ka' => fake()->text], JSON_THROW_ON_ERROR)),
			'image'                => '/images/1668504250_siuu.jpg',
		];
	}
}
