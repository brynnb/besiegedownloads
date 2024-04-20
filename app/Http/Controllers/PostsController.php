<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Input;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Category;
use DB;
use Illuminate\Support\Facades\Event;
use Auth;
use Intervention\Image\ImageManager;
use App\Like;
use App\User;
use Validator;
use AWS;
use App\Ad;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
//        Event::listen('illuminate.query', function($query, $params, $time, $conn) {dd(array($query, $params, $time, $conn));});
        $posts = Post::select('posts.id as id', 'posts.user_id', 'title', 'desc', 'bsg', 'image', 'url', 'downloads', 'category_id', 'likes.id as liked')
            ->joinLikes()
            ->hasThumbnail()
            ->approved()
            ->groupBy('posts.id')
            ->orderBy('posts.id','desc')
            ->paginate(36);

        $categories = Category::all();
        $ads = Ad::orderByRaw("RAND()")->take(8)->get();

        return view('posts.index',['posts'=>$posts, 'categories'=>$categories, 'ads'=>$ads]);

    }

    public function popular()
    {

//        Event::listen('illuminate.query', function($query, $params, $time, $conn) {dd(array($query, $params, $time, $conn));});

        $posts = Post::select('posts.id as id', 'posts.user_id', 'title', 'desc', 'bsg', 'image', 'url', 'downloads', 'category_id', 'likes.id as liked')
            ->joinLikes()
            ->rightJoin(DB::raw('(select post_id, count(id) as total from likes group by post_id) likestotal'),'likestotal.post_id','=','posts.id')
            ->where('total','>',0)
            ->hasThumbnail()
            ->approved()
            ->groupBy('posts.id')
            ->orderBy('total','desc')
            ->paginate(36);

        $page = 'Popular';
        $categories = Category::all();
        $ads = Ad::orderByRaw("RAND()")->take(8)->get();
        $pageTitle = 'Most Popular Uploads';

        return view('posts.index',['posts'=>$posts, 'categories'=>$categories, 'page'=>$page, 'ads'=>$ads, 'pageTitle' => $pageTitle]);
    }

    public function hot()
    {

        $posts = Post::select('posts.id as id', 'posts.user_id', 'title', 'desc', 'bsg', 'image', 'url', 'downloads', 'category_id', 'likes.id as liked')
            ->joinLikes()
            ->rightJoin(DB::raw('(select post_id, count(id) as total from likes where `created_at` > DATE_SUB(now(), INTERVAL 1 DAY) group by post_id) likestotal'),'likestotal.post_id','=','posts.id')
            ->where('total','>',0)
            ->hasThumbnail()
            ->approved()
            ->groupBy('posts.id')
            ->orderBy('total','desc')
            ->paginate(36);
        $page = 'Hot';
        $categories = Category::all();
        $ads = Ad::orderByRaw("RAND()")->take(8)->get();
        $pageTitle = 'Trending Uploads';

        return view('posts.index',['posts'=>$posts, 'categories'=>$categories, 'page'=>$page, 'ads'=>$ads, 'pageTitle' => $pageTitle]);
    }

    public function misc()
    {

        $posts = Post::select('posts.id as id', 'posts.user_id', 'title', 'desc', 'bsg', 'downloads', 'category_id', 'likes.id as liked')
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
            ->whereNotNull('title') //dont need these, changing empty ones to Untitled
            ->where('title','<>','')
            ->groupBy('posts.id')
            ->orderBy('id','desc')
            ->paginate(120);

        $page = 'Misc';
        $categories = Category::all();
        $pageTitle = 'Misc Uploads';

        return view('posts.index',['posts'=>$posts, 'categories'=>$categories, 'page'=>$page, 'pageTitle' => $pageTitle]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
//        if($row['title'] == '') {
//            $title = 'Untitled';
//        } else {
//            $title = htmlspecialchars($row['title']);
//        }
        $pageTitle = 'Upload New Machine';
        return view('posts.create', compact(['pageTitle']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['title' => 'required|min:3|max:50',
            'description' => 'max:4000',
            'type' => 'required',
            'image_file' => 'mimes:jpg,png,jpeg|max:300000',
            'file' => 'max:80000']);

        $validator->after(function($validator)
        {
            if (Input::hasFile('file')) {
                $file = Input::file('file');
                $fileName = $file->getClientOriginalName();

                if (substr($fileName, -4) != '.xml' && substr($fileName, -4) != '.bsg') {
                    $validator->errors()->add('.bsg file', 'The uploaded file must be a .bsg file.');
                } else {
//                    $validator->errors()->add('.bsg file', 'Please re-select your .bsg file (required any time validation fails).');
                }
            } else {
                $validator->errors()->add('.bsg file', 'You must include a .bsg file.');
            }
        });

        if ($validator->fails())
        {
            return redirect('upload')
                ->withErrors($validator)
                ->withInput();
        }

        $uniqueID = uniqid();

        $post = new Post($request->all());
        $post->approved = 0;

        if (Input::hasFile('image_file')) {
//            $post->image = Input::file('image_file');
            $post->approved = 1;
            $post->image = $this->saveImage(Input::file('image_file'), $uniqueID);

        }

        if (Input::hasFile('file')) {
            $file = Input::file('file');
            $fileName = $file->getClientOriginalName();

            //shouldn't ever trigger due to validation but just in case
            if (substr($fileName, -4) != '.bsg' && substr($fileName, -4) != '.xml') {
                abort(400, 'The uploaded file must be a .bsg file.');
            }

            $post->bsg = $this->saveFile($file, $uniqueID);

        }

        $post->category_id = $request['type'];
        $post->desc = $request['description'];
        if(Post::isValidYoutube($request['url'])) //is a proper Youtube URL
        {
            $post->url = $request['url'];
            $post->approved = 1;
        }

        if (Auth::check())
        {
            Auth::user()->posts()->save($post);
        } else {
            $post->save();
        }

        return redirect(action('PostsController@show', ['id'=>$post->id]));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
            if (Auth::check()) {
                if (Auth::user()->isAdmin()) {
                    $isAdmin = true;
                } else {
                    $isAdmin = false;
                }
            } else {
                $isAdmin = false;
            }

//            dd($post->user == Auth::user());
            $pageTitle = $post->title;

            return view('posts.show', compact(['post', 'isAdmin', 'pageTitle']));

        } catch(ModelNotFoundException $e) {
            $message = "Page could not be found. If it was a post, it may have been deleted.";
            return view('errors.404', ['message'=>$message]);
        }


    }

    public function showReroute() {

        try {

            $post = Post::find($_GET['id']);
            if (!$post) {
                $message = "Download could not be found. If it was a post, it may have been deleted.";
                return view('errors.404', ['message'=>$message]);
            }

            return redirect(action('PostsController@show', ['id'=>$post->id]));
            
        } catch(ModelNotFoundException $e) {
            $message = "Download could not be found. If it was a post, it may have been deleted.";
            return view('errors.404', ['message'=>$message]);
        }

    }

    public function download() {

        $post = Post::where('bsg', '=', 'uploads/' . $_GET['id'])->withTrashed()->firstOrFail();

        $post->downloads = $post->downloads + 1;

        $post->save();

        ob_start();
        require(base_path() ."/download.php");
        return ob_get_clean();
//
//        $file="http://s3.besiegedownloads.com/" . $post->bsg;
//        $headers = array(
//            'Content-Type: application/octet-stream',
//            'Content-disposition: attachment;'
//        );
////        return response()->download($file, 'filename.png', $headers);
//        return redirect($file);
////        return 'test';
        //laravel response download external file
        //https://laracasts.com/discuss/channels/general-discussion/flysystem-download-file-from-remote-cdn
    }

    public function showOldFormat()
    {
        if(Input::get('id')) {
            $id = Input::get('id');
            return $this->show($id);
        } else {
            abort(404);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validateForm($request);

        $post = Post::find($id);

        $post->update($request->all());

        if($post->isMisc())
        {
            $post->approved = 0;
        } else {
            $post->approved = 1;
        }

        $post->save();

        return redirect('PostsController@show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {

        $post = Post::find($id);
        $message = '';

        if(Auth::check()){
            if(Auth::user()->isAdmin() || Auth::user()->id == $post->user_id ){
                foreach($post->reports as $report){
                    $report->delete();
                }
                $post->delete();

                $message = 'Your post has been deleted';

            } else {
                $message = 'You are not authorized to delete this post';
            }
        } else {
            $message = 'You are not authorized to delete this post';
        }

        return view('posts.delete', compact('message'));
    }

    public function approve($id)
    {
        if(Auth::check())
        {
            if(Auth::user()->isAdmin())
            {
                $post = Post::find($id);

                if($post->isMisc()){
                    $post->approved = 0; //goes back to default misc value, can be reported again i suppose
                } else {
                    $post->approved = 111; //goes to unreportable value
                }

                $post->save();
                foreach($post->reports as $report)
                {
                    $report->delete();
                }
            }
        }

        return redirect('reports');
    }

    public function sendToMisc($id)
    {
        if(Auth::check())
        {
            if(Auth::user()->isAdmin())
            {
                $post = Post::find($id);
                $post->approved = 0;
                $post->image = null;
                $post->url = null;
                $post->save();
                foreach($post->reports as $report)
                {
                    $report->delete();
                }
            }
        }

        return redirect('reports');
    }

    private function saveImage($file, $uniqueID)
    {

        $manager = new ImageManager(array('driver' => 'gd'));

        $thumbnail = 'uploads/' . $uniqueID . substr($file->getClientOriginalName(), 0, -4) . 'resize400.jpg';
        $thumbnail512 = 'uploads/' . $uniqueID . 'resize512.jpg';
        $full = 'uploads/' . $uniqueID . substr($file->getClientOriginalName(), 0, -4) . 'resize.jpg';

        $image = $manager
            ->make($file->getRealPath())
            ->fit(400,400)
            ->save($thumbnail);
        $image = $manager
            ->make($file->getRealPath())
            ->fit(512,512)
            ->save($thumbnail512);
        $image = $manager
            ->make($file->getRealPath())
            ->widen(700, function ($constraint) {
                $constraint->upsize();
            })
            ->save($full);

        $s3 = AWS::createClient('s3');

        $s3->putObject(array(
            'Bucket'     => 's3.besiegedownloads.com',
            'Key'        => $thumbnail,
            'SourceFile' => $thumbnail,
            'ACL'    => 'public-read'
        ));

        $s3->putObject(array(
            'Bucket'     => 's3.besiegedownloads.com',
            'Key'        => $thumbnail512,
            'SourceFile' => $thumbnail512,
            'ACL'    => 'public-read'
        ));

        $s3->putObject(array(
            'Bucket'     => 's3.besiegedownloads.com',
            'Key'        => $full,
            'SourceFile' => $full,
            'ACL'    => 'public-read'
        ));

        return 'uploads/' . $uniqueID . substr($file->getClientOriginalName(), 0, -4) . 'resize.jpg';

    }
    private function saveFile($file, $uniqueID)
    {

        $name = 'uploads/' . $uniqueID . preg_replace("/[^a-zA-Z0-9-_\.]/", "", $file->getClientOriginalName());

        $file->move('uploads/' , $uniqueID . preg_replace("/[^a-zA-Z0-9-_\.]/", "", $file->getClientOriginalName()));

        $s3 = AWS::createClient('s3');
        $s3->putObject(array(
            'Bucket'     => 's3.besiegedownloads.com',
            'Key'        => $name,
            'SourceFile' => $name,
            'ACL'    => 'public-read'
        ));

        return $name;
    }
}
