<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		User::factory()->hasMovies(3)->create([
			'name'     => 'Oto Macha',
			'email'    => 'oto@mail',
			'password' => bcrypt('12345678'),
		]);

		User::factory()->hasMovies(3)->create([
			'name'     => 'Gio Chiko',
			'email'    => 'chiko@mail',
			'password' => bcrypt('12345678'),
		]);

		Movie::factory(3)->hasQuotes(2)->create();

		Comment::factory(3)->create();

		Genre::create([
			'name'     => 'Drama',
		]);

		Genre::create([
			'name' => 'Comedy',
		]);

		Genre::create([
			'name' => 'Fantasy',
		]);

		Genre::create([
			'name' => 'Horror',
		]);

		Genre::create([
			'name' => 'Romance',
		]);

		Genre::create([
			'name' => 'Thriller',
		]);
	}
}
