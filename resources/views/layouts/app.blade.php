<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?33" rel="stylesheet">

    <link href="/images/favicon.ico" rel="icon" type="image/x-icon">
    <link href="/images/favicon.ico" rel="shortcut icon" type="image/x-icon">

    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    @if($seo && request()->path()=="/")
        <title>@yield('page_title',$seo->title)</title>
        <meta name="description" content="@yield('page_description',$seo->description)">
        <meta property="og:image" content="@yield('page_og_image',Storage::disk("public")->url($seo->image))"/>
        <meta name="Keywords" content="@yield('seo_keys',$seo->keys)">
    @endif
    @stack('seo')
    @if(isset($scripts) && $scripts)
        @foreach($scripts->where("place","header") as $script)
            {!! $script->value !!}
        @endforeach
    @endif

    @if($currency??null)
        <meta name="currency" content="{{$currency}}">
    @endif
    @if($rate??null)
        <meta name="rate" content="{{$rate}}">
    @endif

</head>
<body id="main-body">
<nav id="auth-app" class="navbar navbar-expand-md navbar-dark shadow-sm nav-header navbar-menu-bg fixed-top bg-dark">
    <header-widget
        ref="header-widget"
        @if(Auth::check())
        :user="{{Auth::user()}}"
        @endif
        :product_count="'{{$orderItemsCount}}'"
        :csrf="'{{csrf_token()}}'"
        :games="{{$games}}"
        :currency="'{{$currency}}'"/>
</nav>
@yield('content')
<footer>
    <div class="container py-3">
        <div class="row">
            <div class="col-12 col-sm-2 d-flex align-items-center">
                <img src="/images/logo.png" style="width: 70px">
            </div>
            <div class="col-12 col-sm-6 d-flex align-items-center" style="flex-flow: column;">
                <div class="row " style="width: 100%">
                    <div class="col">
                        <a class="text-white" href="{{url("agreement")}}">User agreement</a>
                    </div>
                    <div class="col">
                        <a class="text-white" href="{{url("details")}}">Company details</a>
                    </div>
                    <div class="col">
                        <a class="text-white" href="{{url("faq")}}">How It Works</a>
                    </div>
                </div>
                <div class="row" style="width: 100%">
                    <div class="col">
                        <a class="text-white" href="{{url("about_us")}}">About us</a>
                    </div>
                    <div class="col">
                        <a class="text-white" href="{{url("terms")}}">Terms of Service</a>
                    </div>
                    <div class="col">
                        <a class="text-white" href="{{url("refund")}}">Refund Policy</a>
                    </div>
                </div>
                <div class="row" style="width: 100%;">
                    <div class="col">
                        <a class="text-white" href="{{url("privacy")}}">Privacy Policy</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4 d-flex align-items-center">
                <a class="nav-link b-r-30 btn-primary nav-link text-center text-white" type="button" data-toggle="modal" data-target="#help-modal">
                    <svg class="bi bi-question-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path
                            d="M5.25 6.033h1.32c0-.781.458-1.384 1.36-1.384.685 0 1.313.343 1.313 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.007.463h1.307v-.355c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.326 0-2.786.647-2.754 2.533zm1.562 5.516c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                    </svg>
                    24/7 Customer support
                </a>
            </div>
        </div>
        <p style="font-size: 10px;margin-top: 10px;">@ Boostmytoon is not endorsed or in any way affiliated with Blizzard Entertainment, Bungie or Respawn Entertainment, and does not reflect the views or opinions of the
            aforementioned entities or anyone officially involved in producing or managing franchises. All trademarks of the aforementioned entities in U.S.A and/or other
            countries. All submitted art content remains copyright of its original copyright holder. Boostmytoon is not selling ingame items, only offers different services to make
            players ingame skill better and gifting them ingame items. 71-75 Shelton Street, Greater London, London, United Kingdom, WC2H 9JQ. @</p>
    </div>
</footer>

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
                    <li class="p-3 d-flex">
                        <img src="/images/icons/email.png" style="width: 30px">&nbsp;
                        <div style="margin-left: 15px">
                            <p style="margin: 0">Email</p>
                            <p style="margin: 0" class="text-primary" data-key="email">support@boostmytoon.com</p>
                        </div>
                        <div class="d-none d-sm-block" style="    margin-left: auto;">
                            <button type="button" class="btn b-r-30 btn-primary js-copy" data-target="email">Copy</button>
                            <a target="_blank" href="mailto:support@boostmytoon.com" class="btn b-r-30 btn-primary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="arrow_forward_ios_24px">
                                        <path id="icon/navigation/arrow_forward_ios_24px"
                                              d="M6.16504 20.13L7.93504 21.9L17.835 12L7.93504 2.10001L6.16504 3.87001L14.295 12L6.16504 20.13H6.16504Z" fill="currentColor"
                                              fill-opacity="1"/>
                                    </g>
                                </svg>

                            </a>
                        </div>
                    </li>
                    <li class="p-3 d-flex">
                        <img src="/images/icons/skype.svg">&nbsp;
                        <div style="margin-left: 15px">
                            <p style="margin: 0">Skype</p>
                            <p style="margin: 0" class="text-primary" data-key="skype">live:boostmytoon</p>
                        </div>
                        <div class="d-none d-sm-block" style="    margin-left: auto;">
                            <button type="button" class="btn b-r-30 btn-primary js-copy" data-target="skype">Copy</button>
                            <a target="_blank" href="skype:live:boostmytoon?chat" type="button" class="btn b-r-30 btn-primary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="arrow_forward_ios_24px">
                                        <path id="icon/navigation/arrow_forward_ios_24px"
                                              d="M6.16504 20.13L7.93504 21.9L17.835 12L7.93504 2.10001L6.16504 3.87001L14.295 12L6.16504 20.13H6.16504Z" fill="currentColor"
                                              fill-opacity="1"/>
                                    </g>
                                </svg>

                            </a>
                        </div>
                    </li>
                    <li class="p-3 d-flex">
                        <img src="/images/icons/discord.svg">
                        <div style="margin-left: 15px">
                            <p style="margin: 0">Discord</p>
                            <p style="margin: 0" class="text-primary" data-key="discord">boostmytoon#3894</p>
                        </div>
                        <div class="d-none d-sm-block" style="    margin-left: auto;">
                            <button type="button" class="btn b-r-30 btn-primary js-copy" data-target="discord">Copy</button>
                            <a target="_blank" href="https://discordapp.com/users/boostmytoon#3894/" class="btn b-r-30 btn-primary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="arrow_forward_ios_24px">
                                        <path id="icon/navigation/arrow_forward_ios_24px"
                                              d="M6.16504 20.13L7.93504 21.9L17.835 12L7.93504 2.10001L6.16504 3.87001L14.295 12L6.16504 20.13H6.16504Z" fill="currentColor"
                                              fill-opacity="1"/>
                                    </g>
                                </svg>

                            </a>
                        </div>
                    </li>

                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}?4"></script>
@stack("scripts")
@include("particles.intercom")
@if(isset($scripts) && $scripts)
    @foreach($scripts->where("place","footer") as $sc)
        {!! $sc->value !!}
    @endforeach
@endif
</body>
</html>
