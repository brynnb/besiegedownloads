@extends('master')

@section('content')

{{--I know this is bad. Sorry --}}
<?php $voted = 'btn-primary'; ?>
@if(Auth::check())
    @foreach(Auth::user()->likes as $like)
        @if($like->post_id == $post->id)
            <?php $voted = 'btn-warning'; ?>
        @endif
    @endforeach
@endif


            <!-- Projects Row -->
            <div class="row">

                @if($post->trashed())
                    <div class="col-lg-12">
                    <div class="alert alert-danger">
                        <b>This item has been deleted.</b> The files may not work or the original images may have been offensive or off-topic.
                    </div>
                    </div>
                @elseif($post->approved == 4)
                    <div class="col-lg-12">
                         <div class="alert alert-danger"><b>This item has been reported.</b> The files may not work or the original images may have been offensive or off-topic. It will be restored when approved by a moderator.
                         </div>
                     </div>
                @endif
                @if(Auth::check())
                    @if(Auth::user()->isAdmin())
                        <div class="col-lg-12 text-center">Report Data (bold means not reviewed):<br/><br/>
                            @foreach($post->reports()->withTrashed()->get() as $report)
                                @if($report->trashed())
                                    {{$report->created_at}}: {{$report->text}}<br/><br/>
                                @else
                                    <b>{{$report->created_at}}: {{$report->text}}<br/><br/></b>
                                @endif

                            @endforeach
                        </div>
                    @endif
                @endif


                <div class="col-lg-12">
                    <div class="panel">
                    <div class="panel-body post">
                        <a download href="/download/?id={{substr($post->bsg,8)}}" class="main-download"><button class="btn btn-primary" type="button"><i class="fa fa-download"></i>&nbsp;DOWNLOAD</button></a>
                        <a class="instructions">How do I use a .bsg file?</a>
                        <h2 class="title">{{$post->title}}</h2>
                        @if(isset($post->user->id))
                            <span class="light"> uploaded by <b><a href="{{action('UsersController@show', $post->user->name)}}">{{$post->user->name}}</a></b> {{$post->created_at->diffForHumans()}}</span>
                        @else
                            <span class="light"> uploaded by anonymous {{$post->created_at->diffForHumans()}}</span>
                        @endif

                        {{--admin and user buttons--}}
                        <div class="pull-right">
                            @if(Auth::check())
                                @if(isset($post->user))
                                    @if(Auth::user()->isAdmin() || Auth::user()->id == $post->user->id)
                                        <button class="btn btn-danger delete-machine" data-href="{{action('PostsController@destroy', $post->id)}}" data-id="{{$post->id}}" type="button"><span class="fa fa-trash"></span>&nbsp;delete</button>
                                    @endif
                                @else
                                    @if(Auth::user()->isAdmin())
                                        <button class="btn btn-danger delete-machine" data-href="{{action('PostsController@destroy', $post->id)}}" data-id="{{$post->id}}" type="button"><span class="fa fa-trash"></span>&nbsp;delete</button>
                                    @endif
                                @endif
                                @if(Auth::user()->isAdmin())
                                    <a href="{{action('PostsController@approve', $post->id)}}"><button class="btn btn-success" type="button"><span class="fa fa-thumbs-o-up"></span>&nbsp;approve</button></a>
                                @endif
                                @if(Auth::user()->isAdmin())
                                    <a href="{{action('PostsController@sendToMisc', $post->id)}}"><button class="btn btn-warning" type="button"><span class="fa fa-folder"></span>&nbsp;send to misc and approve</button></a>
                                @endif
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        @if($post->image != '' && $post->image != null)
                            @if((!($post->trashed()) && $post->approved != '4') || App\User::isAdmin())
                                <img class="main-image" src="http://s3.besiegedownloads.com/{{$post->image}}">
                                <br />
                                <div class="description">
                                    {!! nl2br(e($post->desc)) !!}
                                </div>
                                <div style="clear: both; height: 20px;"></div>
                                @if(App\Post::isValidYoutube($post->url))
                                    <iframe class="main-video" width="600" height="400" src="http://www.youtube.com/embed/{{$post->youtube}}"></iframe>
                                @endif

                            @endif
                        @elseif(App\Post::isValidYoutube($post->url))
                            @if((!($post->trashed())  && $post->approved != '4') || App\User::isAdmin())
                                <div class="description full-width">
                                    {!! nl2br(e($post->desc)) !!}
                                </div>
                                <iframe class="main-video" width="600" height="400" src="http://www.youtube.com/embed/{{$post->youtube}}"></iframe>
                                <div style="clear: both; height: 20px;"></div>
                            @endif
                        @else
                            @if((!($post->trashed())  && $post->approved != '4') || App\User::isAdmin())
                            <div class="description full-width">
                                {!! nl2br(e($post->desc)) !!}
                            </div>
                            @endif
                        @endif


                        <div class="pull-left share-link">
                            Link to share BSG: <input type="textbox" onclick="this.select()" value="http://www.besiegedownloads.com/download/?id={{substr($post->bsg,8)}}">
                        </div>
                        <div class="pull-right actions">
                            <a download href="/download/?id={{substr($post->bsg,8)}}"><button class="btn btn-primary" type="button"><span class="badge entries score">{{$post->downloads}}</span>&nbsp;DOWNLOADS</button></a>
                            @if(!Auth::check())
                                <a href="/like/{{$post->id}}">
                                    <button class="btn btn-primary" data-id="{{$post->id}}" type="button"><span data-id="{{$post->id}}" class="badge entries score">{{$post->total_likes}}</span> LIKES</button>
                                </a>
                            @else
                                @if($voted == 'btn-warning')
                                    <button class="btn {{$voted}} like" data-id="{{$post->id}}" type="button"><span data-id="{{$post->id}}" class="badge entries score">{{$post->total_likes}}</span> YOU LIKED</button>
                                @else
                                    <button class="btn {{$voted}} like" data-id="{{$post->id}}" type="button"><span data-id="{{$post->id}}" class="badge entries score">{{$post->total_likes}}</span> LIKES</button>
                                @endif
                            @endif
                            <a href="{{action('ReportsController@create', $post->id)}}"><button class="btn btn-primary" type="button"><span class="fa fa-warning"></span>&nbsp;REPORT</button></a>

                        </div>
                            <div style="clear: both; height: 20px;"></div>
                    </div>
                    </div>
                </div>

                <div class="col-xs-12 listing-comments">



                    <div class="comments-container">

                        {{--display comments --}}
                        @foreach($post->comments as $comment)
                            @include('posts.comment')
                        @endforeach

                        @if(sizeof($post->comments) == 0)
                            No comments to display.
                        @endif


                    </div>

                </div>

                <div class="col-xs-12 listing-submit-comment">

                    @if(Auth::check())
                        {!! Form::open(['url' => 'comments']) !!}
                        @include('errors/list')
                        {!! Form::hidden('post_id', $value = $post->id) !!}
                        {!! Form::textarea('text', null, ['class'=>'form-control', 'rows'=>'5', 'placeholder'=>'Post a comment']) !!}
                        <div class="form-group">
                            <br />
                            {!! Form::submit('Submit Comment', ['class'=> 'btn btn-primary btn-block pull-right submit-comment']) !!}
                        </div>

                        {!! Form::close() !!}

                    @else
                        <a href="/auth/login"><textarea disabled rows="5" class="form-control comment-value" placeholder="Login to post comments"></textarea></a>
                    @endif

                </div>

            </div>





            </div>



@endsection