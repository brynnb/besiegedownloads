<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Report;
use Input;
use App\Post;
use Auth;
use Log;

class ReportsController extends Controller
{
    public function create($id)
    {
        $pageTitle = 'Report Content';
        return view('reports.create', compact(['id', 'pageTitle']));
    }

    public function index()
    {
        $reports = Report::all();
        $pageTitle = 'Report Content';
        return view('reports.index', ['reports' => $reports]);
    }

    public function submitted()
    {
        return view('reports.submitted');
    }

    public function delete($id) {
        $report = Report::find($id);
        $report->delete();
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        $report = new Report($request->all());
        $report->post_id = Input::get('post_id');

        if(Auth::check())
        {
            $report->user_id = Auth::user()->id;
        }
        $report->save();

        $post = Post::find($report->post_id);
        if($post->approved == 1 || $post->approved == 0)
        {
            $post->approved = 4;
            $post->save();
        }

        Log::debug('ERRORREPORTSUBMITED');

        return redirect(action('ReportsController@submitted'));

    }

    private function validateForm($request)
    {
        $this->validate($request, ['text' => 'required|max:1000|min:5', 'check'=>'required|numeric|digits_between:1,2']);
    }
}
