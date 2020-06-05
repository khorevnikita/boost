<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css?1') }}" rel="stylesheet">

    @if($seo)
        <title>@yield('page_title',$seo->title)</title>
        <meta name="description" content="@yield('page_description',$seo->description)">
        <meta property="og:image" content="@yield('page_og_image',Storage::disk("public")->url($seo->image))"/>
        <meta name="Keywords" content="@yield('seo_keys',$seo->keys)">
    @endif
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-white shadow-sm">
    <div class="container">
        <a href="{{url("/")}}" class="nav-link bg-dark d-md-none d-lg-none d-xl-none" style="border-radius: 25px">
            <img class="img-fluid" src="/images/logo.png" style="width: 45px">
        </a>
        <button class="navbar-toggler bg-dark" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto d-md-none d-lg-none d-xl-none">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       v-pre>
                        Games <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @foreach($games as $game)
                            <a class="dropdown-item" href="{{url($game->rewrite)}}">
                                {{$game->title}}
                            </a>
                        @endforeach
                    </div>
                </li>
                <li class="nav-item">
                    <a role="button" class="nav-link text-dark">
                        Search
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{url("/order")}}" role="button" class="nav-link text-dark">
                        My cart
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{url("register")}}" class="nav-link text-dark">
                        Profile
                    </a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       v-pre>
                        {{strtoupper($currency)}} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{url("currency/eur")}}">
                            EUR
                        </a>
                        <a class="dropdown-item" href="{{url("currency/usd")}}">
                            USD
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div id="app">
    <div>
        <nav class=" d-none d-md-block bg-dark sidebar sidebar-main">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{url("/")}}" class="nav-link">
                            <img class="img-fluid" src="/images/logo.png">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="button" class="nav-link js-menu-toggle">
                            <img class="img-fluid" src="/images/icons/menu.svg">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="button" class="nav-link">
                            <img src="/images/icons/search.svg" class="img-fluid">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url("/order")}}" role="button" class="nav-link">
                            <img src="/images/icons/shop.svg" class="img-fluid">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url("register")}}" class="nav-link">
                            <img src="/images/icons/auth.svg" class="img-fluid">
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="fixed-list" style="display: none">
            <nav class="col-md-3 d-none d-md-block bg-light sidebar sidebar-games">
                <div class="sidebar-sticky">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Games:</strong></li>
                        @foreach($games as $game)
                            <li class="list-group-item">
                                <a href="{{url($game->rewrite)}}" class="text-muted">
                                    <strong>{{$game->title}}</strong>
                                    <img src="/images/icons/left_arrow.svg" class="img-fluid">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </nav>
        </div>
        <main>
            @yield('content')

            <footer class="bg-white pt-5 pb-5 mt-3">
                <div class="container">
                    <div class="row text-center">
                        <div class="col">
                            <a href="{{url("agreement")}}">User agreement</a>
                        </div>
                        <div class="col">
                            <a href="{{url("details")}}">Company details</a>
                        </div>
                        <div class="col">
                            <a href="{{url("faq")}}">FAQ</a>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@stack("scripts")
</body>
</html>
