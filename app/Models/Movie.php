<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
	use HasFactory;

	protected $guarded = ["id"];

	protected $with = ['genres', 'quotes'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function genres(): BelongsToMany
	{
		return $this->belongsToMany(Genre::class, 'genre_movie', 'movie_id', 'genre_id');
	}

	public function quotes(): HasMany
	{
		return $this->hasMany(Quote::class);
	}

	protected function name(): Attribute
	{
		return Attribute::make(
			get: fn ($value) => json_decode($value, true),
			set: fn ($value) => json_encode($value)
		);
	}

	protected function description(): Attribute
	{
		return Attribute::make(
			get: fn ($value) => json_decode($value, true),
			set: fn ($value) => json_encode($value)
		);
	}

	protected function director(): Attribute
	{
		return Attribute::make(
			get: fn ($value) => json_decode($value, true),
			set: fn ($value) => json_encode($value)
		);
	}
}
