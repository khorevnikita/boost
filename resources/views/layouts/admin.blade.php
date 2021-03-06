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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm nav-header">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    @can("update-content")
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("/admin/pages")}}">Pages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("/admin/banners")}}">Banners</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("/admin/games")}}">Games</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("/admin/products")}}">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("/admin/options")}}">Options</a>
                        </li>
                    @endcan
                    @can("update-users")
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("/admin/users")}}">Users</a>
                        </li>
                    @endcan
                    @can("update-orders")
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("/admin/orders")}}">Orders</a>
                        </li>
                    @endcan
                    @can("update-content")
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("/admin/promocodes")}}">Promo codes</a>
                        </li>
                    @endcan

                    @can("update-content")
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("/admin/seo")}}">SEO</a>
                        </li>
                    @endcan
                    @can("update-content")
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("/admin/scripts")}}">Scripts</a>
                        </li>
                    @endcan

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4" style=" margin-top: 65px;">
        @yield('content')
    </main>
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.js"></script>
@stack("scripts")
</body>
</html>
