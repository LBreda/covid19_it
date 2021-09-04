<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    <script src="https://kit.fontawesome.com/3fbc50dc3b.js" crossorigin="anonymous"></script>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

        {{-- Configured Stylesheets --}}
        @include('adminlte::plugins', ['type' => 'css'])

        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @else
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @endif

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireStyles
        @else
            <livewire:styles/>
        @endif
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}"/>
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}"/>
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/192.png') }}">
        <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicons/512.png') }}">
        <link href="{{ asset('splashscreens/iphone5_splash.png') }}"
              media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)"
              rel="apple-touch-startup-image"/>
        <link href="{{ asset('splashscreens/iphone6_splash.png') }}"
              media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)"
              rel="apple-touch-startup-image"/>
        <link href="{{ asset('splashscreens/iphoneplus_splash.png') }}"
              media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)"
              rel="apple-touch-startup-image"/>
        <link href="{{ asset('splashscreens/iphonex_splash.png') }}"
              media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)"
              rel="apple-touch-startup-image"/>
        <link href="{{ asset('splashscreens/iphonexr_splash.png') }}"
              media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)"
              rel="apple-touch-startup-image"/>
        <link href="{{ asset('splashscreens/iphonexsmax_splash.png') }}"
              media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)"
              rel="apple-touch-startup-image"/>
        <link href="{{ asset('splashscreens/ipad_splash.png') }}"
              media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)"
              rel="apple-touch-startup-image"/>
        <link href="{{ asset('splashscreens/ipadpro1_splash.png') }}"
              media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)"
              rel="apple-touch-startup-image"/>
        <link href="{{ asset('splashscreens/ipadpro3_splash.png') }}"
              media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)"
              rel="apple-touch-startup-image"/>
        <link href="{{ asset('splashscreens/ipadpro2_splash.png') }}"
              media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)"
              rel="apple-touch-startup-image"/>
        <meta name="msapplication-TileColor" content="#343a40">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/144.png') }}">
        <link rel="manifest" href="{{ asset('webmanifest.json') }}">
        <script type="text/javascript">
            // Initialize the service worker
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/serviceworker.js', {
                    scope: '.'
                }).then(function (registration) {
                    // Registration was successful
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function (err) {
                    // registration failed :(
                    console.log('ServiceWorker registration failed: ', err);
                });
            }
        </script>
    @endif
</head>

<body class="@yield('classes_body')" @yield('body_data')>
{{-- Body Content --}}
@yield('body')

{{-- Base Scripts --}}
@if(!config('adminlte.enabled_laravel_mix'))
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    {{-- Configured Scripts --}}
    @include('adminlte::plugins', ['type' => 'js'])

    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@else
    <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
@endif

{{-- Livewire Script --}}
@if(config('adminlte.livewire'))
    @if(app()->version() >= 7)
        @livewireScripts
    @else
        <livewire:scripts/>
    @endif
@endif

{{-- Custom Scripts --}}
@yield('adminlte_js')
</body>
</html>
