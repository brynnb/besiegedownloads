<div class="col-lg-3 col-md-4 col-xs-6">
    <div class="item-container">
        @if($post->image != '')
            <div class="item-thumbnail">
                <a href="{{action('PostsController@show', ['id'=>$post->id])}}">
                    {{--<img height="400" width="400" src="http://s3.besiegedownloads.com/{{$post->thumbnail}}" class="listing-image-thumbnail img-responsive center-block">--}}
                    <img height="400" width="400" src="/img/trans512.gif" class="listing-image-thumbnail img-responsive center-block youtube-thumbnail" style="background-image: url('http://s3.besiegedownloads.com/{{$post->thumbnail}}'); background-repeat: no-repeat; background-size: cover;">
                </a>

                @if(App\Post::isValidYoutube($post->url)) {{-- add play button --}}
                <a class="fancybox-media" href="http://www.youtube.com/embed/{{$post->youtube}}?autoplay=1">
                    <div id="{{$post->youtube}}" class="play"></div>
                </a>
                @endif
            </div>
        @elseif(App\Post::isValidYoutube($post->url))
            <div class="item-thumbnail">
                <a href="{{action('PostsController@show', ['id'=>$post->id])}}">
                    <img height="400" width="400" src="/img/trans512.gif" class="listing-image-thumbnail img-responsive center-block youtube-thumbnail" style="background-image: url('http://i.ytimg.com/vi/{{$post->youtube}}/hqdefault.jpg');">
                </a>

                {{--add play button--}}
                <a class="fancybox-media" href="http://www.youtube.com/embed/{{$post->youtube}}?autoplay=1">
                    <div id="{{$post->youtube}}" class="play"></div>
                </a>
            </div>
        @endif

        <div class="item-text">
            <div class="item-title">
                <span class="fa fa-{{$post->category ? $post->category->icon : ''}}"></span>
                <a href="{{action('PostsController@show', ['id'=>$post->id])}}">{{$post->title}}</a>
            </div>

            @if(isset($post->user->id))
                <span class="light">
                             uploaded by <a href="{{action('UsersController@show', $post->user->name)}}">{{$post->user->name}}</a>
                </span>
                @if(Auth::user())
                    @if(Auth::user()->isAdmin() || Auth::user()->id == $post->user->id)
                        <button class="btn btn-danger delete-machine-index" data-href="{{action('PostsController@destroy', $post->id)}}" data-id="{{$post->id}}" type="button"><span class="fa fa-trash"></span></button></a>
                    @endif
                @endif
            @else
                <span class="light"> uploaded by anonymous</span>
            @endif

            <hr>
            <div class="item-desc">{!! nl2br(e($post->desc)) !!}</div>

            <br /><br />

            <div class="item-action-container">

                <div class="item-action">
                    <a download href="/download/?id={{substr($post->bsg,8)}}">
                        <span class="fa fa-download"></span>&nbsp;{{$post->downloads}}
                    </a>
                </div>
                <div class="item-action">
                    <a href="{{action('PostsController@show', ['id'=>$post->id])}}">
                        <span class="fa fa-comment"></span>&nbsp;{{$post->total_comments}}
                    </a>
                </div>

                @if($post->liked)
                    <div class="item-action like voted" data-id="{{$post->id}}">
                        @else
                            <div class="item-action like" data-id="{{$post->id}}">
                                @endif
                                @if(!Auth::check())
                                    <a href="{{action('Auth\AuthController@getLogin')}}">
                                        @endif
                                        <span class="fa fa-heart entries"></span>&nbsp;
                                        <span data-id="{{$post->id}}" class="score">{{$post->total_likes}}</span>
                                        @if(!Auth::check())
                                    </a>
                                @endif
                            </div>


                            <br />

                            <span class="item-action">
                        <a download href="/download/?id={{substr($post->bsg,8)}}">
                            <span class="fa fa-download"></span>&nbsp;download
                        </a>
                    </span>
                            <span class="item-action report" data-id="{{$post->id}}">
                        <a href="{{action('ReportsController@create', ['id'=>$post->id])}}">
                            <span class="fa fa-warning"></span>&nbsp;report
                        </a>
                    </span>



                            @if(false) {{--if(($row['user_id'] == $userID && $userID != 0) || $userID == 2) { --}}
                            <span class ="item-action delete" data-id="' . $row['id'] . '">
                        <span class="fa fa-trash"></span>&nbsp;delete
                    </span>
                            @endif

                            @if(false) {{--if($userID == '2') {--}}
                            <span class ="item-action approve" data-id="' . $row['id'] . '">
                        <span class="fa fa-check"></span>&nbsp;approve
                    </span>
                            <span class ="item-action misc" data-id="' . $row['id'] . '">
                        <span class="fa fa-close"></span>&nbsp;misc
                    </span>
                            @endif

                    </div>
            </div>
        </div>
    </div>