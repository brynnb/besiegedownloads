<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Category extends Model
{

//    protected $fillable = ['icon'];

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public static function findByNameOrFail($name, $columns = array('*'))
    {
        if ( ! is_null($category = static::whereName($name)->first($columns))) {
            return $category;
        }

        throw new ModelNotFoundException;
    }

}
