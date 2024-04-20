
<div class="panel panel-default comment">
    <div class="panel-body">
        <span class="comment-by">
            posted by <b><a href="{{action('UsersController@show', $comment->user->name)}}">{{$comment->user->name}}</a></b> {{$comment->created_at->diffForHumans()}}
{{--            posted by <a href="/user.php?username={{$comment->user->name}}">{{$comment->user->name}}</a> {{$comment->created_at->diffForHumans()}}--}}
        </span>
        <br />
        {!! nl2br(e($comment->text)) !!}
        <br/>
        <span class="options"></span>
    </div>
</div>
		