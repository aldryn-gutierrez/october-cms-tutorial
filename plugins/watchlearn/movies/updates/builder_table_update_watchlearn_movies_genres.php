<?php namespace Watchlearn\Movies\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateWatchlearnMoviesGenres extends Migration
{
    public function up()
    {
        Schema::rename('watchlearn_movies_genre', 'watchlearn_movies_genres');
        Schema::table('watchlearn_movies_genres', function($table)
        {
            $table->increments('id')->unsigned(false)->change();
            $table->string('genre_title')->change();
            $table->string('slug')->change();
        });
    }
    
    public function down()
    {
        Schema::rename('watchlearn_movies_genres', 'watchlearn_movies_genre');
        Schema::table('watchlearn_movies_genre', function($table)
        {
            $table->increments('id')->unsigned()->change();
            $table->string('genre_title', 191)->change();
            $table->string('slug', 191)->change();
        });
    }
}
