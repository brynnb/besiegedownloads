<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Category;
use App\User;
use App\Post;
use DB;
use Illuminate\Support\Facades\Event;

class CategoriesController extends Controller
{
    public function show($name)
    {
        $category = Category::findByNameOrFail($name);
//        Event::listen('illuminate.query', function($query, $params, $time, $conn) {dd(array($query, $params, $time, $conn));});
        $posts = $category->posts()
            ->select('posts.id as id', 'posts.user_id', 'title', 'desc', 'bsg', 'image', 'url', 'downloads', 'category_id', 'likes.id as liked')
            ->joinLikes()
            ->hasThumbnail()
            ->approved()
            ->groupBy('posts.id')
            ->orderBy('posts.id','desc')
            ->paginate(36);

        $page = '';
        $categories = Category::all();
        $pageTitle = ucfirst($name) . ' Uploads';

        return view('posts.index',['posts'=>$posts, 'categories'=>$categories, 'page'=>$page]);
    }

    public function showHot($name)
    {
        //$user = User::findByUsernameOrFail($name);
        $category = Category::findByNameOrFail($name);

        $posts = $category->posts()->select('posts.id as id', 'posts.user_id', 'title', 'desc', 'bsg', 'image', 'url', 'downloads', 'category_id', 'likes.id as liked')
            ->joinLikes()
            ->rightJoin(DB::raw('(select post_id, count(id) as total from likes where `created_at` > DATE_SUB(now(), INTERVAL 1 DAY) group by post_id) likestotal'),'likestotal.post_id','=','posts.id')
            ->where('total','>',0)
            ->hasThumbnail()
            ->approved()
            ->groupBy('posts.id')
            ->orderBy('total','desc')
            ->paginate(36);

        $page = 'Hot';
        $pageTitle = 'Trending Uploads';
        $categories = Category::all();

        return view('posts.index',['posts'=>$posts, 'categories'=>$categories, 'page'=>$page, 'pageTitle' => $pageTitle]);
    }

    public function showPopular($name)
    {
//            Event::listen('illuminate.query', function($query, $params, $time, $conn)
//        {
//            dd(array($query, $params, $time, $conn));
//       });

        $categories = Category::all();
        //$user = User::findByUsernameOrFail($name);
        $category = Category::findByNameOrFail($name);
        $posts = $category->posts()->select('posts.id as id', 'posts.user_id', 'title', 'desc', 'bsg', 'image', 'url', 'downloads', 'category_id', 'likes.id as liked')
            ->joinLikes()
            ->rightJoin(DB::raw('(select post_id, count(id) as total from likes group by post_id) likestotal'),'likestotal.post_id','=','posts.id')
            ->where('total','>',0)
            ->hasThumbnail()
            ->approved()
            ->groupBy('posts.id')
            ->orderBy('total','desc')
            ->paginate(36);

        $page = 'Popular';
        $pageTitle = 'Most Popular Uploads';
//    return 'hi';
        return view('posts.index',['posts'=>$posts, 'categories'=>$categories, 'page'=>$page, 'pageTitle' => $pageTitle]);
    }

    public function showMisc($name)
    {
        //$user = User::findByUsernameOrFail($name);
        $category = Category::findByNameOrFail($name);

        $posts = $category->posts()->select('posts.id as id', 'posts.user_id', 'title', 'desc', 'bsg', 'downloads', 'category_id', 'likes.id as liked')
            ->joinLikes()
            ->where(function($query) {
                $query->where('image', '=', '')
                    ->orWhere('image','=',null);
            })
            ->where(function($query) {
                $query->where('url', '=', '')
                    ->orWhere('url','=',null);
            })
            ->isApprovedMisc()
            ->whereNotNull('title')
            ->where('title','<>','')
            ->groupBy('posts.id')
            ->orderBy('id','desc')
            ->paginate(120);

        $page = 'Misc';
        $pageTitle = 'Miscellaneous Uploads';
        $categories = Category::all();

        return view('posts.index',['posts'=>$posts, 'categories'=>$categories, 'page'=>$page, 'pageTitle' => $pageTitle]);
    }
}
