<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function getSalt()
    {
        return $this->attributes['salt'];
    }

    public static function findByUsernameOrFail($username, $columns = array('*'))
    {
        if ( ! is_null($user = static::whereName($username)->first($columns))) {
            return $user;
        }

        throw new ModelNotFoundException;
    }

    public static function isAdmin()
    {
        if(Auth::check()) {
            if(Auth::user()->id == 2)
            {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getUserID()
    {
        if(Auth::check())
        {
            return Auth::user()->id;
        } else {
            return null;
        }
    }


    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function likes()
    {
        return $this->hasMany('App\Like');
    }
}
