<?php namespace Watchlearn\Movies\Components;

use Cms\Classes\ComponentBase;
use Watchlearn\Movies\Models\Actor;


class Actors extends ComponentBase
{
	public function componentDetails()
	{
		return [
			'name' => 'Actor list',
			'description' => 'List of actors',
		];
	}

	public function defineProperties()
	{
		return [
			'results' => [
				'title' => 'Number of Actors',
				'description' => 'How many actors do you want to display?',
				'default' => 0,
				'validationPattern' => '^[0-9]+$',
				'validationMessage' => 'Only numbers allowed',
			],
			'sortOrder' => [
				'title' => 'Sort Actors',
				'description' => 'Sort those actors',
				'type' => 'dropdown',
				'default' => 'name asc',
			],
		];
	}

	public function onRun()
	{
		$this->actors = $this->loadActors();
	}

	protected function loadActors()
	{
		$query = Actor::all();

		$sortOrder = $this->property('sortOrder');
		if ($sortOrder == 'name asc') {
			$query = $query->sortBy('name');
		} else if ($sortOrder == 'name desc') {
			$query = $query->sortByDesc('name');
		}

		$limit = $this->property('results');
		if ($limit > 0) {
			$query = $query->take($limit);
		}

		return $query;
	}

	public function getSortOrderOptions()
	{
		return [
			'name asc' => 'Name (ascending)',
			'name desc' => 'Name (descending)',
		];
	}

	public $actors;
}