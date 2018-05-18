<?php namespace Watchlearn\Movies\Models;

use Model;

/**
 * Model
 */
class Movie extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'watchlearn_movies_';

    // protected $jsonable = ['actors'];

    /* Relations */

    public $attachOne = [
        'poster' => 'System\Models\File'
    ];

    public $attachMany = [
        'movie_gallery' => 'System\Models\File'
    ];

    public $belongsToMany = [
        'genres' => [
            'Watchlearn\Movies\Models\Genre',
            'table' => 'watchlearn_movies_movies_genres',
            'order' => 'genre_title',
        ],
        'actors' => [
            'Watchlearn\Movies\Models\Actor',
            'table' => 'watchlearn_movies_actors_movies',
            'order' => 'name',
        ]
    ];

    public static $allowedSortingOptions = [
        'name desc' => 'Name - desc',
        'name asc' => 'Name - asc',
        'year desc' => 'Year - desc',
        'year asc' => 'Year - asc',
    ];

    public function scopeListFrontEnd($query, $options = [])
    {
        extract(
            array_merge(
                [
                    'page' => 1,
                    'perPage' => 10,
                    'sort' => 'created_at desc',
                    'genres' => null,
                    'year' => '',
                ],
                $options
            )
        );

        if (!is_array($sort)) {
            $sort = [$sort];
        }

        foreach ($sort as $_sort){
            if(in_array($_sort, array_keys(self::$allowedSortingOptions))){
                $parts = explode(' ', $_sort);

                if(count($parts) < 2){
                    array_push($parts, 'desc');
                }

                list($sortField, $sortDirection) = $parts;

                $query->orderBy($sortField, $sortDirection);
            }
        }

        if ($genres !== null) {

            if (!is_array($genres)) {
                $genres = [$genres];
            }

            foreach ($genres as $genre) {
                $query->whereHas('genres', function($q) use ($genre) {
                    $q->where('id', '=', $genre);
                });
            }
        }

        if ($year) {
            $query->where('year', '=', $year);
        }

        $lastPage = $query->paginate($perPage, $page)->lastPage();
        if ($lastPage < $page) {
            $page = 1;
        }

        return $query->paginate($perPage, $page);
    }
}
