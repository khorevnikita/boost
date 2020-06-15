<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-162422290-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-162422290-1');
    </script>

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
<nav class="navbar navbar-expand-md navbar-dark bg-white shadow-sm nav-header" style="position: absolute;width: 100%;z-index: 99;">
    <div class="container">
        <a href="{{url("/order")}}" class="btn btn-dark position-relative d-md-none">
            <img src="/images/icons/shop.svg" class="img-fluid">
            <span class="badge badge-primary price-badge @if(!$orderItemsCount) d-none @endif">{{$orderItemsCount}}</span>
        </a>
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
                    <a role="button" class="nav-link text-dark js-search">
                        Search
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
                <li class="nav-item">
                    <a class="nav-link text-dark" type="button" data-toggle="modal" data-target="#help-modal">
                        <svg class="bi bi-question-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path
                                d="M5.25 6.033h1.32c0-.781.458-1.384 1.36-1.384.685 0 1.313.343 1.313 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.007.463h1.307v-.355c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.326 0-2.786.647-2.754 2.533zm1.562 5.516c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                        </svg>
                        24/7 Customer support
                    </a>
                </li>
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
    <div style=" margin-top: 65px;">
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
                        <a role="button" class="nav-link js-search">
                            <img src="/images/icons/search.svg" class="img-fluid">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url("/order")}}" role="button" class="nav-link position-relative">
                            <img src="/images/icons/shop.svg" class="img-fluid">
                            <span class="badge badge-primary price-badge @if(!$orderItemsCount) d-none @endif">{{$orderItemsCount}}</span>
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
        <main class="bg-white">
            @yield('content')

            <footer class=" pt-5 pb-5 mt-3" style="background-color: #f8fafc !important;">
                <div class="container mt-3 mb-3">
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
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">24/7 Customer support</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled">
                    {{--<li>
                        Telephone number: <a class="text-dark" href="tel:79032382548"><strong>+7 903 238 25 48</strong></a>
                    </li>
                    <li>
                        Company email: <a class="text-dark" href="mailto:info@boostmytoon.com"><strong>info@boostmytoon.com</strong></a>
                    </li>--}}
                    <li class="p-3">

                        <strong>
                            <a style="text-decoration: none" class="text-dark" target="_blank" href="https://m.me/106316304455180">
                                <img src="/images/icons/fb_messenger.svg">&nbsp;
                                Facebook
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="arrow_forward_ios_24px">
                                        <path id="icon/navigation/arrow_forward_ios_24px"
                                              d="M6.16504 20.13L7.93504 21.9L17.835 12L7.93504 2.10001L6.16504 3.87001L14.295 12L6.16504 20.13H6.16504Z" fill="black"
                                              fill-opacity="1"></path>
                                    </g>
                                </svg>
                            </a>
                        </strong>
                    </li>
                    <li class="p-3">
                        <strong>
                            <a style="text-decoration: none" class="text-dark" target="_blank" href="skype:live:msgbooster">
                                <img src="/images/icons/skype.svg">&nbsp;
                                Skype
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="arrow_forward_ios_24px">
                                        <path id="icon/navigation/arrow_forward_ios_24px"
                                              d="M6.16504 20.13L7.93504 21.9L17.835 12L7.93504 2.10001L6.16504 3.87001L14.295 12L6.16504 20.13H6.16504Z" fill="black"
                                              fill-opacity="1"></path>
                                    </g>
                                </svg>
                            </a>
                        </strong>
                        {{--Skype:
                        <a role="button" class="js-copy" data-target="skype">
                            <strong data-key="skype">live:boostmytoon</strong>
                            <svg class="bi bi-files" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M3 2h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H3z"/>
                                <path d="M5 0h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1H3a2 2 0 0 1 2-2z"/>
                            </svg>
                        </a>--}}

                    </li>
                    <li class="p-3">
                        <a role="button" class="js-copy" data-target="discord">
                            <strong><img src="/images/icons/discord.svg">&nbsp; <span data-key="discord">boostmytoon#3894</span></strong>
                            <svg class="bi bi-files" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M3 2h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H3z"/>
                                <path d="M5 0h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1H3a2 2 0 0 1 2-2z"/>
                            </svg>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
@stack("scripts")
@include("particles.intercom")
</body>
</html>
