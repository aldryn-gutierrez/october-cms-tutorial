<?php

namespace Watchlearn\Movies\Components;

use Cms\Classes\ComponentBase;
use Input;
use Redirect;
use Watchlearn\Movies\Models\Movie;
use Watchlearn\Movies\Models\Genre;


class FilterMovies extends ComponentBase 
{
	public function componentDetails()
	{
		return [
			'name' => 'Filter movies',
			'description' => 'Filter movies',
		];
	}

	public function onRun()
	{
		$this->movies = $this->filterMovies();
		$this->years = $this->loadYears();
		$this->genres = $this->loadGenres();
	}

	protected function filterMovies()
	{
		$genre = Input::get("genre");
		$year = Input::get("year");
		$query = new Movie();

		if ($year) {
			$query = $query->where('year', '=', $year);
		}

		if ($genre) {
			$query = $query->whereHas('genres', function ($query) use ($genre) {
				$query->where('slug', '=', $genre);
			});
		}

		return $query->get();
	}

	protected function loadYears()
	{
		return Movie::select('year')
			->distinct()
			->orderBy('year')
			->get()
			->pluck('year')
			->all();
	}

	protected function loadGenres()
	{
		return Genre::select('genre_title', 'slug')
			->get()
			->all();
	}

	public $movies;

	public $years;

	public $genres;
}
