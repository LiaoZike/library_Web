<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{config('admin.web_icon')}}" sizes="64x64" />

    <title>{{config('admin.title')}} | {{ trans('admin.login') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @if(!is_null($favicon = Admin::favicon()))
        <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/font-awesome/css/font-awesome.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css") }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/iCheck/square/blue.css") }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<style>
</style>
<!-- <body class="hold-transition login-page" @if(config('admin.web_icon'))style="background: url({{config('admin.web_icon')}}) ;"@endif> -->
<body class="hold-transition login-page" @if(config('admin.web_icon')) style="background: url('{{ url(config('admin.web_icon')) }}') no-repeat center center fixed; background-size: cover;" @endif>
<div class="login-box cool-border">
    <div class="login-logo" style="margin-bottom: 0px;">
        <a href="{{ admin_url('/') }}" style="font-size: 19px;">{{config('admin.name')}}</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body" >
        <p>請輸入帳號、密碼</p>

        <form action="{{ admin_url('auth/login') }}" method="post">
            @csrf
            <div class="form-group has-feedback {!! !$errors->has('username') ?: 'has-error' !!}">

                @if($errors->has('username') || $errors->has('password'))
                    <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> 無效的帳號或密碼！&nbsp;&nbsp; ( ╯' - ')╯ ┻━┻</label><br>
                @endif

                <input type="text" class="form-control" placeholder="{{ trans('admin.username') }}" name="username" value="{{ old('username') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback {!! !$errors->has('password') ?: 'has-error' !!}">
                <input type="password" class="form-control" placeholder="{{ trans('admin.password') }}" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

{{--            <div class="form-group has-feedback {!! !$errors->has('captcha') ?: 'has-error' !!}">--}}
{{--                @if($errors->has('captcha'))--}}
{{--                    @foreach($errors->get('captcha') as $message)--}}
{{--                        <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>驗證碼錯誤！ &nbsp;&nbsp;(´・ω・`)</label><br>--}}
{{--                    @endforeach--}}
{{--                @endif--}}
{{--                <input type="text" class="form-control" placeholder="驗證碼" name="captcha">--}}
{{--                <img style="margin-top: 5px;border: 1px solid #000000;cursor: pointer;" src="{{ captcha_src() }}" alt="點擊刷新" onclick="this.src='{{ url('captcha/default') }}?s='+Math.random()"> <br/>--}}
{{--                <span class="glyphicon glyphicon-ice-lolly form-control-feedback"></span>--}}
{{--            </div>--}}

            <div class="row btnF mt-3">
                <!-- /.col -->
                <div class="col-md-12">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn-primary" style="padding: 5px 20px">{{ trans('admin.login') }}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js")}}"></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js")}}"></script>
<!-- iCheck -->
<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js")}}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
<style>
    a{
        text-decoration: none;
    }
    @keyframes rgbCycle {
        0% { box-shadow: 0 0   3px 3px rgba(255, 0, 0, 1), 0 0 20px rgba(255, 0, 0, 1)}
        33% { box-shadow: 0 0  3px 3px rgba(0, 255, 0, 1), 0 0 20px rgba(0, 255, 0, 1)}
        67% { box-shadow: 0 0  3px 3px rgba(0, 0, 255, 1), 0 0 20px rgba(0, 0, 255, 1)}
        100% { box-shadow: 0 0 3px 3px rgba(255, 0, 0, 1), 0 0 20px rgba(255, 0, 0, 1)}
    }
    .login-logo{
        background-color: #515151;
    }
    .login-logo a {
        color:white;
    }
    .login-box{
        /* animation: rgbCycle 5s linear infinite; */
        box-shadow: 0 0  3px 3px rgba(255, 255, 255, 1), 0 0 20px rgba(255, 255, 255, 1)
    }
    .cool-border{
        border: 1px solid black;
        border-radius: 3px;
        overflow: auto;
        /* box-shadow:  0px 0px 3px 2px #c605ff; */
        box-shadow: 0 0  3px 3px rgba(255, 255, 255, 1), 0 0 20px rgba(255, 255, 255, 1)

    }
    button{
        background-color: rgba(0, 0, 0, 0.91);
        color: #ffffff;
        padding: 6px 0px;
        transition: background-color .4s;
        font-size: medium;
    }
    button:hover{
        background-color: #b8b8b8;
        color: #000000;
    }
    .form-group:nth-child(1) input{
        margin-bottom: 5px;
    }
    .form-group:nth-child(2) input{
        margin-bottom: 20px;
    }
    body.hold-transition.login-page {
        position: relative;
        background: url('{{ url(config('admin.web_icon')) }}') no-repeat center center fixed;
        background-size: cover;
        overflow: hidden;
    }

    body.hold-transition.login-page::before {
        content: "";
        position: fixed;
        inset: 0;
        background-color: rgba(10, 10, 10, 0.90); /* 0.5 代表黑色透明度，越大越暗 */
        z-index: -1;
    }
</style>
</body>
</html>
