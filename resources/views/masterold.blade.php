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

    <title>Besiege Downloads - Share your favorite machines</title>

</head>
<body>

    <div class="container-fluid">



        <div class="row">
            <div class="col-xs-12" id="login-links">
                <div id="login-link">



                    {{-- if logged in --}}
                    @if (Auth::check())
                        <a href="{{action('UsersController@show', Auth::user()->name)}}">
                            <i class="fa fa-user"></i>&nbsp;{{ Auth::user()->name }}
                        </a>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{action('Auth\AuthController@getLogout')}}">
                            <i class="fa fa-sign-out"></i> logout
                        </a>
                    @else
                        <a href="{{action('Auth\AuthController@getRegister')}}">
                            <i class="fa fa-users"></i> register
                        </a>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{action('Auth\AuthController@getLogin')}}">
                            <i class="fa fa-sign-out"></i> sign-in
                        </a>
                    @endif
                    {{--}} } else {
                         echo '<a href="/register.php"><i class="fa fa-users"></i> register</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/login.php"><i class="fa fa-sign-in"></i> login</a>';
                     }--}}
                    @if(Auth::check())
                         @if(Auth::user()->isAdmin())
                             <a href="{{action('ReportsController@index')}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-warning"></i> modqueue</a>
                         @endif
                    @endif



                </div>
            </div>

            <div class="col-xs-12" id="banner-container">
                <a href="/">
                    <div id="banner">
                        Besiege Downloads
                    </div>
                </a>

            </div>
        </div>
        <div class="row">
            <div id="navbar" class="col-xs-12 text-center">
                <div class="navbar-container">
                    <a href="{{action('PostsController@index')}}"><div class="navitem {{isActiveRoute('home')}}"><span class="fa fa-certificate"></span>&nbsp;NEWEST</div></a>
                    <a href="{{action('PostsController@hot')}}"><div class="navitem {{isActiveRoute('hot')}}"><span class="fa fa-line-chart"></span>&nbsp;HOT TODAY</div></a>
                    <a href="{{action('PostsController@popular')}}"><div class="navitem {{isActiveRoute('popular')}}"><span class="fa fa-thumbs-o-up"></span>&nbsp;POPULAR</div></a>
                    <a href="{{action('PostsController@misc')}}"><div class="navitem {{isActiveRoute('misc')}}"><span class="fa fa-folder-open-o"></span>&nbsp;MISC</div></a>
                    <a href="{{action('PostsController@create')}}"><div class="navitem {{isActiveRoute('upload')}}"><span class="fa fa-upload"></span>&nbsp;UPLOAD</div></a>
                </div>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            @if(isset($categories))
                <div class="text-center col-lg-12 pills-container">

                    <ul class="nav nav-pills machine-type">
                        @if($page == '') {{--determining which page the 'All Items' filter goes to--}}
                            <li role="presentation" class="{{isAllItems()}}">
                                <a href="{{action('PostsController@index')}}">
                                    <span class="fa fa-filter"></span>&nbsp;All Items
                                </a>
                            </li>
                        @else
                            <li role="presentation" class="{{isAllItems()}}">
                                <a href="{{action('PostsController@' . strtolower($page))}}">
                                    <span class="fa fa-filter"></span>&nbsp;All Items
                                </a>
                            </li>
                        @endif
                        @foreach($categories as $category)
                            <li role="presentation" class="{{isActiveRoute(Illuminate\Support\Str::lower($category->name), 'active')}}">
                                <a href="{{action('CategoriesController@show' . $page, Illuminate\Support\Str::lower($category->name))}}">
                                    <span class="fa fa-{{$category->icon}}"></span>&nbsp;{{$category->name}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(isset($posts))
                @if($posts->currentPage() == 1 && $page == '' && isAllItems())
                <div class="col-lg-12 header-desc">
                    <div class="desc-container">
                        <p>Besiege Downloads serves the <a href="http://www.besiege.spiderlinggames.co.uk/" target="_blank">Besiege</a> community by being a central repository of BSG files used to save in-game machines.
                            BSG files are made by Besiege when you save a war machine or vehicle. They can be opened and modified with a text editor just like a text file, but it's not very straightforward to do so.
                            Besiege is a physics based puzzle game made by Spiderling Games and is currently in alpha. It is <a href="http://store.steampowered.com/app/346010/" target="_blank">available on Steam</a> as an Early Access Game.
                            <br/ ><br/ >
                            You can use any saved machines found here by downloading the .bsg file and saving it to the "SavedMachines" folder inside the Besiege installation folder. It is often located here:<br />
                            C:\Program Files (x86)\Steam\SteamApps\common\Besiege\Besiege_Data\SavedMachines
                        </p>
                    </div>
                </div>
                @endif
            @endif


            <div class="col-lg-10 col-lg-offset-1">
                <div class="items-container container-fluid">
                    @yield('content')
                </div>
            </div>


        </div>

        <div class="row">
            <div class="col-xs-12 text-center" id="copyright">
                &copy; Copyright 2015 Besiege Downloads. All rights reserved. Not affiliated with Spiderling Studios.
                <a href="mailto:email@besiegedownloads.com">email@besiegedownloads.com</a>
            </div>
        </div>


    </div>

    <!-- jQuery -->
    <script src="/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/js/bootstrap.min.js"></script>

    <script src="/js/functions6.js"></script>
    <script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
    <script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
    <script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    <script type="text/javascript">
        //var _globalObj = {!! json_encode(array('_token'=> csrf_token())) !!};
        var loggedIn = {{Auth::check() ?: 0}};
    </script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-3180076-13', 'auto');
        ga('send', 'pageview');

    </script>


</body>

</html>
