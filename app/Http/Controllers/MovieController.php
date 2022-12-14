<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
	public function index(): JsonResponse
	{
		$movies = jwtUser()->movies;

		$movieDetails = $movies->map(function ($movie) {
			return $movie->only(['id', 'name', 'image', 'year', 'quotes']);
		});

		return response()->json(['movies' => $movieDetails], 200);
	}

	public function store(StoreMovieRequest $request): JsonResponse
	{
		$file_name = '/images/' . time() . '_' . $request->file('image')->getClientOriginalName();
		$request->file('image')->storePubliclyAs('public', $file_name);

		$movie = Movie::create([
			'name'        => [
				'en' => $request->name_en,
				'ka' => $request->name_ka,
			],
			'director'        => [
				'en' => $request->director_en,
				'ka' => $request->director_ka,
			],
			'description'        => [
				'en' => $request->description_en,
				'ka' => $request->description_ka,
			],
			'user_id'     => jwtUser()->id,
			'budget'      => $request->budget,
			'year'        => $request->year,
			'image'       => $file_name,
		]);

		$genres = explode(',', $request->genre);
		$movie->genres()->attach($genres);

		return response()->json(['status' => 'Movie added successfully'], 200);
	}

	public function show(Movie $movie): JsonResponse
	{
		return response()->json(['movie' => $movie], 200);
	}

	public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
	{
		$movie->update([
			'name'        => [
				'en' => $request->name_en,
				'ka' => $request->name_ka,
			],
			'director'        => [
				'en' => $request->director_en,
				'ka' => $request->director_ka,
			],
			'description'        => [
				'en' => $request->description_en,
				'ka' => $request->description_ka,
			],
			'user_id'     => jwtUser()->id,
			'budget'      => $request->budget,
			'year'        => $request->year,
		]);

		$genres = explode(',', $request->genre);
		$movie->genres()->sync($genres);

		if ($request->hasFile('image'))
		{
			$file_name = '/images/' . time() . '_' . $request->file('image')->getClientOriginalName();
			$request->file('image')->storePubliclyAs('public', $file_name);
			$movie->update(['image' => $file_name]);
		}
		return response()->json(['status' => 'Movie updated successfully'], 200);
	}

	public function destroy(Movie $movie): JsonResponse
	{
		$movie->delete();

		return response()->json(['status' => 'Movie deleted successfully'], 200);
	}

	public function getGenres(): JsonResponse
	{
		return response()->json(['genres' => Genre::all()], 200);
	}

	public function search(): JsonResponse
	{
		if (request('keyword'))
		{
			$keyword = request('keyword');

			$movies = jwtUser()->movies()
						->where('name->en', 'LIKE', '%' . $keyword . '%')
						->orWhere('name->ka', 'LIKE', '%' . $keyword . '%')->get();

			return response()->json(['movies' => $movies], 200);
		}

		return response()->json(['movies' => jwtUser()->movies], 200);
	}
}
