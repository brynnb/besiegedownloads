<div class="col-lg-3 col-md-4 col-xs-6">

        <div class="tile">
            @if($post->image != '' || App\Post::isValidYoutube($post->url))
            <div class="share-stack">

                <p class="fa-lg share-link">
                    @if(!Auth::check())
                        <a href="/like/{{$post->id}}"><button class="btn btn-primary" data-id="{{$post->id}}"><i class="fa fa-heart"></i> LIKE THIS</button></a>
                    @else
                        @if($post->liked)
                            <button class="btn btn-warning voted like" data-id="{{$post->id}}"><i class="fa fa-heart"></i> YOU LIKED</button>
                        @else
                            <button class="btn btn-primary like" data-id="{{$post->id}}"><i class="fa fa-heart"></i> LIKE THIS</button>
                        @endif
                    @endif
                </p>
                <p class="fa-lg share-link">
                    <a download href="/download/?id={{substr($post->bsg,8)}}"><button class="btn btn-primary" type="button"><i class="fa fa-download"></i>&nbsp;DOWNLOAD THIS</button></a>
                </p>

            </div>
            @endif
            @if($post->image != '')
                <img class="img-responsive result-thumbnail" src="{{$post->thumbnail}}"/>
                @if(App\Post::isValidYoutube($post->url)) {{-- add play button --}}
                <a class="fancybox-media" href="http://www.youtube.com/embed/{{$post->youtube}}?autoplay=1">
                    <div id="{{$post->youtube}}" class="play"></div>
                </a>
                @endif
            @elseif(App\Post::isValidYoutube($post->url))
                <div class="video-thumb-container">
                    <img class="img-responsive result-thumbnail" src="http://i.ytimg.com/vi/{{$post->youtube}}/hqdefault.jpg"/>
                </div>
                <a class="fancybox-media" href="http://www.youtube.com/embed/{{$post->youtube}}?autoplay=1">
                    <div id="{{$post->youtube}}" class="play"></div>
                </a>
            @else

                <div class="misc-desc-thumb">
                    <div class="text">
                    {!! nl2br(e($post->desc)) !!}
                    </div>
                </div>
            @endif
            <a class="tile-link" href="{{action('PostsController@show', ['id'=>$post->id])}}">
                <span class="tile-overlay"></span>
            </a>
            <div class="footer">
                <span class="title">{{$post->title}}</span>
                <div class="bottom">
                    @if(isset($post->user->id))
                    <span class="store"> by <a href="{{action('UsersController@show', $post->user->name)}}">{{$post->user->name}}</a></span>
                    @else
                        <span class="store"> by anonymous</span>
                    @endif
                    <div class="actions"></div>
                    <div class="stats"><span class="score" data-id="{{$post->id}}">{{$post->total_likes}}</span>&nbsp;<i class="fa fa-heart"></i>   {{$post->downloads}}&nbsp;<i class="fa fa-download"></i></div>
                </div>
            </div>
        </div>

</div>