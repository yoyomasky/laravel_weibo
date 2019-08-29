@extends('layouts.default')
@section('title', '首页')
@section('content')
    <div class="jumbotron">
        <h1>Hello Laravel weibo</h1>
        <p class="lead">
            你现在所看到的是 Somewhere所制作的 <a href="https://learnku.com/courses/laravel-essential-training">Laravel weibo </a> 的示例项目主页。
        </p>
        <p>
            一切，将从这里开始。
        </p>
        <p>
            <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">现在注册</a>
        </p>
    </div>
@stop
