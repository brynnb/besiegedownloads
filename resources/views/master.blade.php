<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="description" content="download and share your favorite war machines, vehicles, or contraptions for Besiege, the warfare physics simulator">
    <meta name="keywords" content="besiege,downloads,uploads,vehicles,sharing,social,bsg,files,downloading,uploading,catapults,war,machines,steam,early,access,flamethrower,bombs,physics,guides,challenges,contests,entries">
    <meta name="author" content="besiege downloads">
    <meta name="_token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,700,300,100' rel='stylesheet' type='text/css'>
    <link href="{{ elixir('css/all.css') }}" rel="stylesheet">
    @if(isset($pageTitle))
        <title>{{$pageTitle}} | Besiege Downloads</title>
    @else
        <title>Besiege Downloads - Share your favorite machines</title>
    @endif

</head>

<!--Body-->
<body>


<!--Header-->
<header>
    <body>
    <div class="cover">
        <div class="navbar">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-ex-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="logo" href="/">
                        <img src="/img/banner-new5.png" alt="Besiege Downloads"/>
                        <img src="/img/besiegebannermobile.png" class="hidden visible-xs mobile-logo img-responsive" alt="Besiege Downloads"/>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="navbar-ex-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown-header hidden visible-xs">USER</li>
                        <li>
                            <a class="btn btn-default upload"  href="{{action('PostsController@create')}}">
                                UPLOAD MACHINE
                            </a>
                        </li>
                        @if(Auth::check())
                            {{--<li>--}}
                            {{--<a class="btn btn-default upload"  href="{{action('UserController@index')}}">--}}
                            {{--Edit Machines--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            <li>
                                <a class="btn btn-default upload"  href="{{action('UsersController@show', Auth::user()->name)}}">
                                    MY PAGE
                                </a>
                            </li>
                            <li>
                                <a class="btn btn-default" href="{{action('Auth\AuthController@getLogout')}}">
                                    LOGOUT
                                </a>
                            </li>
                            @if(Auth::user()->isAdmin())
                                <li>
                                    <a class="btn btn-default upload"  href="/reports">
                                        MODQUEUE
                                    </a>
                                </li>
                            @endif
                        @else
                            <li>
                                <a class="btn btn-default"  href="{{action('Auth\AuthController@getRegister')}}">
                                    REGISTER
                                </a>
                            </li>
                            <li>
                                <a class="btn btn-default" href="{{action('Auth\AuthController@getLogin')}}">
                                    LOGIN
                                </a>
                            </li>
                        @endif
                        <li class="dropdown-header hidden visible-xs">PAGES</li>
                        <li>
                            <a class="btn btn-default hidden visible-xs {{isActiveRoute('home')}}" href="{{action('PostsController@index')}}">
                                Newest
                            </a>
                        </li>
                        <li>
                            <a class="btn btn-default hidden visible-xs {{isActiveRoute('hot')}}" href="{{action('PostsController@hot')}}">
                                Trending
                            </a>
                        </li>
                        <li>
                            <a class="btn btn-default hidden visible-xs {{isActiveRoute('popular')}}" href="{{action('PostsController@popular')}}">
                                Most Popular
                            </a>
                        </li>
                        <li>
                            <a class="btn btn-default hidden visible-xs {{isActiveRoute('misc')}}" href="{{action('PostsController@misc')}}">
                                Misc
                            </a>
                        </li>
                        <li class="dropdown-header hidden visible-xs">CATEGORIES</li>
                        @if($page == '') {{--determining which page the 'All Items' filter goes to--}}
                        <li>
                            <a class="btn btn-default hidden visible-xs {{isAllItems()}}" href="{{action('PostsController@index')}}">
                                Show All
                                {{--<span class="fa fa-filter"></span>&nbsp;--}}
                            </a>
                        </li>
                        @else
                            <li>
                                <a class="btn btn-default hidden visible-xs {{isAllItems()}}" href="{{action('PostsController@' . strtolower($page))}}">
                                    Show All
                                    {{--<span class="fa fa-filter"></span>&nbsp;--}}
                                </a>
                            </li>
                        @endif
                        @foreach($categories as $category)
                            <li>
                                <a class="btn btn-default hidden visible-xs {{isActiveRoute(Illuminate\Support\Str::lower($category->name))}}" href="{{action('CategoriesController@show' . $page, Illuminate\Support\Str::lower($category->name))}}">
                                    </span>&nbsp;{{$category->name}}
                                    {{--<span class="fa fa-{{$category->icon}}">--}}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="header-woodbar">

    </div>

</header><!--Header Close-->

<div class="container main-container">
    <div class="main-content">
        @include('flash::message')
        @include('common.errors')
        @yield('content')
    </div>
</div>


<!--Footer-->
<footer class="footer text-center">
    <div class="container">
        <div class="copyright">
            <div class="row text-center">
                <p>&copy; 2015 - {!! date("Y") !!} Besiege Downloads. Not affiliated with Spiderling Studios.</p>
                <img src="/img/address.png">
            </div>
        </div>
    </div>
</footer><!--Footer Close-->


        <!-- Latest compiled and minified JavaScript -->
<script src="{{ elixir('js/all.js') }}"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-3180076-13', 'auto');
  ga('send', 'pageview');

</script>
</body><!--Body Close-->
</html>
