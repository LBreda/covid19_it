<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <script src="https://kit.fontawesome.com/3fbc50dc3b.js" crossorigin="anonymous"></script>

    @include('adminlte::plugins', ['type' => 'css'])

    @yield('adminlte_css_pre')

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    @yield('adminlte_css')

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    @yield('meta_tags')

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
        <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
        <meta name="msapplication-TileColor" content="#343a40">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/144.png') }}">
    @endif
</head>
<body class="@yield('classes_body')" @yield('body_data')>

@yield('body')

@if(! config('adminlte.enabled_laravel_mix'))
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    @include('adminlte::plugins', ['type' => 'js'])

    @yield('adminlte_js')
@else
    <script src="{{ mix('js/app.js') }}"></script>
@endif

</body>
</html>
