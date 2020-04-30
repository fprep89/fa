<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if($_SERVER['HTTP_HOST'] == 'onlinelibrary.test' || $_SERVER['HTTP_HOST'] == 'piofx.com' )
      <link rel="shortcut icon" href="{{asset('/favicon_piofx.ico')}}" />
    @else
      <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" />
    @endif

    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffc40d">
    <meta name="theme-color" content="#71bce2">
    <!-- CSRF Token -->
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="author" content="First Academy Team">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @if(isset($player))
    <link rel='stylesheet' href='{{ asset("css/player.css") }}'>
    @endif
    <!-- Styles -->
    @if($_SERVER['HTTP_HOST'] == 'project.test' || $_SERVER['HTTP_HOST'] == 'prep.firstacademy.in')
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    @else
    <link href="{{ asset('css/styles_piofx.css') }}" rel="stylesheet">
    @endif
    @if(isset($editor))
    <link href="{{asset('js/summernote/summernote-bs4.css')}}" rel="stylesheet">
    @endif
    @if(isset($try) || isset($reading))
    <link rel='stylesheet' href='{{ asset("css/try.css") }}'>
    @endif

    @if(isset($try))
      <script type="text/x-mathjax-config">
          MathJax.Hub.Config({
          extensions: ["tex2jax.js"],
          jax: ["input/TeX","output/HTML-CSS"],
          tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
      });
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    @endif
</head>
<body >
    <div id="app">
        @include('layouts.menu')

        
        <main class="main">
            @yield('content')
        </main>
        <footer class="bg-white footer">
            <div class="container">
            @include('layouts.footer')
        </div>
        @if(isset($toast))
        @include('blocks.toast')
        @endif
        @include('layouts.script')
        </footer>

    </div>
</body>
</html>
