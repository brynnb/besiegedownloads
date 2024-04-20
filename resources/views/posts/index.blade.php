@extends('master')

@section('content')

    @include('common.errors')

    @if(isset($posts))
        @if($posts->currentPage() == 1 && Request::is('/'))
            <div class="row">
            <div class="col-lg-12 header-desc">
                <div class="desc-container">
                    <div class="panel">
                        <div class="panel-body">
                    <h4>Welcome</h4>
                    <p>
                        Besiege Downloads is a repository of machines, as uploaded by their creators in a '.bsg' file , for a PC game called Besiege. <a href="#" class="read-more-toggle pull-right">Read More <i class="fa fa-toggle-down"></i></a>
                    </p>
                    <p class="read-more">
                        <a href="http://www.besiege.spiderlinggames.co.uk/" target="_blank">Besiege</a> is a physics based puzzle game made by Spiderling Games and is currently in alpha. It is <a href="http://store.steampowered.com/app/346010/" target="_blank">available on Steam</a> as an Early Access Game. BSG files are made by Besiege when you save your machine, vehicle, or creation. They can be opened and modified with a text editor just like a text file (it uses an XML format), but it's not very straightforward to do so.
                        <br/ ><br/ >
                        You can use any saved machines found here by downloading the .bsg file and saving it to the "SavedMachines" folder inside the Besiege installation folder. It is often located here:<br />
                        <input type="text" value="C:\Program Files (x86)\Steam\SteamApps\common\Besiege\Besiege_Data\SavedMachines" readonly>
                    </p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        @endif
    @endif

    @if(isset($indexTitle))
        <div class="row">
            <div class="col-sm-12">
                <h1>{{$indexTitle}}</h1>
            </div>
        </div>
    @else
        <div class="row hidden-xs">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-10">
                        <ul class="nav nav-pills">
                            <li role="presentation"><a class="{{isActiveRoute('home')}}" href="{{action('PostsController@index')}}">NEWEST</a></li>
                            <li role="presentation"><a class="{{isActiveRoute('hot')}}" href="{{action('PostsController@hot')}}">TRENDING</a></li>
                            <li role="presentation"><a class="{{isActiveRoute('popular')}}" href="{{action('PostsController@popular')}}">MOST POPULAR</a></li>
                            <li role="presentation"><a class="{{isActiveRoute('misc')}}" href="{{action('PostsController@misc')}}">MISC</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-2">
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                CATEGORIES <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @if($page == '') {{--determining which page the 'All Items' filter goes to--}}
                                <li>
                                    <a class="{{isAllItems()}}" href="{{action('PostsController@index')}}">
                                        SHOW ALL
                                        {{--<span class="fa fa-filter"></span>&nbsp;--}}
                                    </a>
                                </li>
                                @else
                                    <li>
                                        <a class="{{isAllItems()}}" href="{{action('PostsController@' . strtolower($page))}}">
                                            SHOW ALL
                                            {{--<span class="fa fa-filter"></span>&nbsp;--}}
                                        </a>
                                    </li>
                                @endif
                                <li role="separator" class="divider"></li>
                                @foreach($categories as $category)
                                    <li>
                                        <a class="{{isActiveRoute(Illuminate\Support\Str::lower($category->name))}}" href="{{action('CategoriesController@show' . $page, Illuminate\Support\Str::lower($category->name))}}">
                                            </span>&nbsp;{{Illuminate\Support\Str::upper($category->name)}}
                                            {{--<span class="fa fa-{{$category->icon}}">--}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    @endif



    <section class="result-grid">


        <div class="row">

            @if(sizeof($posts) > 0)
                @foreach($posts as $i => $post)
                    @include('posts._result')

                    @if($i % 9 == 0)
                        @if(isset($ads))
                            @include ('ads.ad', ['ad'=> $ads[(($i) / 6)], 'post'=>$posts[($i - 1)]])
                        @endif
                    @endif

                @endforeach
            @else
                <div class="col-lg-12">
                    <h4>No uploads found for this user or category.</h4>
                </div>
            @endif

        </div>


        <div class="text-center">
            {!! $posts->render() !!}
        </div>

        @if(isset($comments) && sizeof($comments) > 0)
            <h1>Recent Comments</h1>
            <div class="comments-container">
            @foreach($comments as $c)

                <div class="panel panel-default comment">
                    <div class="panel-body">
                    <span class="comment-by">
                        posted by <b><a href="{{action('UsersController@show', $c->name)}}">{{$c->name}}</a></b> {{$c->created_at->diffForHumans()}} on <b><a href="{{action('PostsController@show', ['id'=>$c->post_id])}}">{{$c->title}}</a></b>
                    </span>
                    <br />
                    {!! nl2br(e($c->text)) !!}
                    <br/>
                    <span class="options"></span>
                    </div>
                </div>
            @endforeach
            </div>
        @endif




    </section><!--Result Grid Close-->
@endsection