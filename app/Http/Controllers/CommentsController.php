<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Comment;
use Auth;
use App\Post;
use Input;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        $this->validateForm($request);

//        dd($request);

        $comment = new Comment($request->all());
        $comment->post_id = Input::get('post_id');

        if (Auth::check())
        {
            Auth::user()->comments()->save($comment);
        }

        return redirect(action('PostsController@show', ['id'=>$comment->post_id]));

    }

    private function validateForm($request)
    {
        $this->validate($request, ['text' => 'max:3000|required']);
    }
}
