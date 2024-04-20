<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Post;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($name)
    {
        $user = User::findByUsernameOrFail($name);
        $posts = Post::where('user_id','=',$user->id)->paginate(36);
        $comments = Comment::select('comments.text', 'comments.post_id', 'comments.created_at', 'users.name as name', 'posts.title as title', 'posts.user_id')
            ->leftJoin('users', 'users.id', '=', 'comments.user_id')
            ->leftJoin('posts', 'posts.id', '=', 'comments.post_id')
            ->having('posts.user_id', '=', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(30)->get();

        //$posts = $user->posts;//->paginate(30);

        $indexTitle = $user->name . "'s Uploads";
        $pageTitle = $user->name . "'s Uploads";

        return view('posts.index', compact(['posts', 'indexTitle', 'comments', 'pageTitle']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
