<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(['quotes' => Quote::latest()->with('movie')->paginate(2)], 200);
	}

	public function store(StoreQuoteRequest $request): JsonResponse
	{
		$file_name = '/images/' . time() . '_' . $request->file('image')->getClientOriginalName();
		$request->file('image')->storePubliclyAs('public', $file_name);

		Quote::create([
			'name'        => [
				'en' => $request->name_en,
				'ka' => $request->name_ka,
			],
			'image'        => $file_name,
			'movie_id'     => $request->movie_id,
			'user_id'      => jwtUser()->id,
		]);

		return response()->json(['status' => 'Quote added successfully'], 200);
	}

	public function show(Quote $quote): JsonResponse
	{
		return response()->json(['quote' => $quote], 200);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
	{
		$quote->update([
			'name'        => [
				'en' => $request->name_en,
				'ka' => $request->name_ka,
			],
			'movie_id'     => $request->movie_id,
		]);

		if ($request->hasFile('image'))
		{
			$file_name = '/images/' . time() . '_' . $request->file('image')->getClientOriginalName();
			$request->file('image')->storePubliclyAs('public', $file_name);
			$quote->update(['image' => $file_name]);
		}

		return response()->json(['status' => 'Quote updated successfully'], 200);
	}

	public function destroy(Quote $quote): JsonResponse
	{
		$quote->delete();

		return response()->json(['status' => 'Quote deleted successfully'], 200);
	}

	public function search(): JsonResponse
	{
		if (request('keyword'))
		{
			$keyword = request('keyword');

			if ($keyword[0] === '@')
			{
				$quotes = Quote::whereHas('movie', function ($query) use ($keyword) {
					$query->where('name->en', 'LIKE', '%' . substr($keyword, 1) . '%')
						  ->orWhere('name->ka', 'LIKE', '%' . substr($keyword, 1) . '%');
				})->with('movie')->get();

				return response()->json(['quotes' => $quotes], 200);
			}

			if ($keyword[0] === '#')
			{
				$quotes =
					Quote::Where('name->en', 'LIKE', '%' . substr($keyword, 1) . '%')
					->orWhere('name->ka', 'LIKE', '%' . substr($keyword, 1) . '%')
					->with('movie')->get();

				return response()->json(['quotes' => $quotes], 200);
			}
		}
		return response()->json(['quotes' => Quote::latest()->with('movie')->get()], 200);
	}
}
