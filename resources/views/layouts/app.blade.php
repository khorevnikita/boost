<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-162422290-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-162422290-1');
    </script>
    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '394692321943326');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=394692321943326&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
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
<nav id="auth-app" class="navbar navbar-expand-md navbar-dark shadow-sm nav-header navbar-menu-bg" style="position: absolute;width: 100%;z-index: 99;">
    <header-widget
        @if(Auth::check())
        :user="{{Auth::user()}}"
        @endif
        :product_count="'{{$orderItemsCount}}'"
        :csrf="'{{csrf_token()}}'"
        :games="{{$games}}"
        :currency="'{{$currency}}'"/>
</nav>
@yield('content')
<!-- Scripts -->

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
                            <a style="text-decoration: none" class="text-dark" target="_blank" href="https://m.me/boostmytoon">
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
<script src="{{ asset('js/app.js') }}?3"></script>
@stack("scripts")
@include("particles.intercom")
</body>
</html>
