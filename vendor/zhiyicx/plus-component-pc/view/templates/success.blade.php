{{--@section('title')--}}
    {{--资讯--}}
{{--@endsection--}}

@extends('pcview::layouts.default')

@section('content')
    <div class="success">
        <img src="{{ $status == 1 ? asset('zhiyicx/plus-component-pc/images/pay_pic_succeed.png') : asset('zhiyicx/plus-component-pc/images/pay_pic_failed.png')}}" alt="">
        <div class="content">
            <div class="success-message">{{$message or '操作成功'}}</div>
            <div class="success-content">
                {{$content or '操作成功！'}}，
                <span id="redirect-time">{{$time or 10}}</span>s后自动返回
            </div>
            <a href="{{$url}}" class="success-button">返回</a>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var redirectTime = $('#redirect-time');
        var time = parseInt(redirectTime.text());
        // 时间递减
        function displayTime(){
            redirectTime.text(time);
            time --;
        }

        // 跳转页
        function redirect(){
            window.location.href="{{$url}}";//指定要跳转到的目标页面
        }

        setInterval('displayTime()', 1000);//显示时间

        setTimeout('redirect()',time * 1000); //跳转

    </script>
@endsection
