<?php namespace Watchlearn\Movies\Models;

use Model;

/**
 * Model
 */
class Actor extends Model
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
    public $table = 'watchlearn_movies_actors';

    public $belongsToMany = [
        'movies' => [
            'Watchlearn\Movies\Models\Movie',
            'table' => 'watchlearn_movies_actors_movies',
            'order' => 'name',
        ]
    ];

    public function getFullnameAttribute()
    {
        return $this->name.' '.$this->lastname;
    }

    public $attachOne = [
        'actorimage' => 'System\Models\File',
    ];
}
