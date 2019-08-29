<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Weibo App') - Laravel 学习</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
@include('layouts._header')


<div id="app" class="container">
    <div class="offset-md-1 col-md-10 ">
        <div class='body-interval'></div>
        @include('shared._messages')
        @yield('content')
        @include('layouts._footer')
    </div>
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
