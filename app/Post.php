<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'desc',
        'url',
        'category_id',
        'bsg',
        'image'
    ];

    public function scopeApproved($query)
    {
        return $query->where(function($query) {
            $query->where('approved', '=', 1)
                ->orWhere('approved','=',111);
        });
    }

    public function scopeIsApprovedMisc($query)
    {
        return $query->where('approved','<>','111')
            ->where('approved','<>','1')
            ->where('approved','<>','3')
            ->where('approved','<>','4');
    }

    public function scopeHasThumbnail($query)
    {
        return $query->where(function($query) {
            $query->where('image', '<>', '')
                ->orWhere('url','<>','');
        });
    }

    public function scopeJoinLikes($query)
    {
        return $query->leftJoin('likes', function($query) {
            $query->on('likes.post_id','=','posts.id')
                ->where('likes.user_id','=',User::getUserID());
        });
    }

    public function getTotalCommentsAttribute()
    {
        return $this->hasMany('App\Comment')->count();
    }

    public function getTotalLikesAttribute()
    {
        return $this->hasMany('App\Like')->count();
    }

    public function getYoutubeAttribute()
    {
        preg_match("/(?<=(?:v|i)=)[a-zA-Z0-9-]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^\"&\n]+|(?<=(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+/", $this->url, $output_array);
        if(isset($output_array[0]))
        {
            return $output_array[0];
        } else {
            return '';
        }
    }

    public static function isValidYoutube($link)
    {
        preg_match("/(?<=(?:v|i)=)[a-zA-Z0-9-]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^\"&\n]+|(?<=(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+/", $link, $output_array);
        if(isset($output_array[0]))
        {
            return true;
        } else {
            return false;
        }
    }

    public function getThumbnailAttribute()
    {
//        return substr(substr($this->image, 0, -4),0,21) . 'resize400.jpg';
        return env('AWS_DOMAIN') . '/' . substr($this->image, 0, 21) . 'resize512.jpg';
    }

    public function getIconAttribute() //not sure this gets used,
    // found different method since categorycontroller was making no icons show up (I think this is fixed now?)
    {
        if(isset($this->category))
        {
            return $this->category->icon;
        } else {
            return '';
        }

    }

    public function isMisc()
    {
        if($this->getYoutubeAttribute() == '' && ($this->image == '' || $this->image == null))
        {
            return true;
        } else {
            return false;
        }


    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function reports()
    {
        return $this->hasMany('App\Report');
    }
}
