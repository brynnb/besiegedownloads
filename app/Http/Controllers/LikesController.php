<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Like;
use Auth;
use App\Post;

class LikesController extends Controller
{
//    public function loginRedirect(Request $request, $id) {
//
//        if($request->session()->get('redirectURL')) {
//            return $request->session()->get('redirectURL');
//        } else {
//            $request->session()->flash('redirectURL', redirect()->back());
//        }
//        return 'woo';
//
//    }
//
//    public function loginPreRediect(Request $request, $id) {
//
//        if($request->session()->get('redirectURL')) {
//            return $request->session()->get('redirectURL');
//        } else {
//            $request->session()->flash('redirectURL', redirect()->back());
//        }
//        return 'woo';
//
//    }
//
    public function like(Request $request, $id)
    {

        if(Auth::check()) {
            $found = false;

            foreach(Auth::user()->likes as $like)
            {
                if($like->post_id == $id)
                {
                    $found = true;
                }
            }

            if(!$found){
                $like = new Like();
                $like->post_id = $id;
                $like->user_id = Auth::user()->id;
                $like->save();
            }

        }

        return redirect($request->session()->get('redirectURL'));

    }
}
