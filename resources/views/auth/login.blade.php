@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-xs-4 col-xs-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">Вход</div>
            <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <fieldset class="form-group">
                        <div class="col-xs-12">
                            <span class="account-content-block__header">Войти через</span>
                            <p>
                                <a href="#" class="social-icon social-icon_link social-icon_vk "></a>
                                <a href="#" class="social-icon social-icon_link social-icon_ok "></a>
                                <a href="#" class="social-icon social-icon_link social-icon_fb "></a>
                                <a href="#" class="social-icon social-icon_link social-icon_gplus "></a>
                            </p>
                        </div>
                    </fieldset>

                    <fieldset class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                        <div class="col-xs-12">
                            <input id="login" type="text" class="form-control" name="login"
                                   value="{{ old('login') }}" placeholder="Имя пользователя, телефон или email"
                                   autofocus>

                            @if ($errors->has('login'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('login') }}</strong>
                                </span>
                            @endif
                        </div>
                    </fieldset>

                    <fieldset class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <div class="col-xs-12">
                            <input id="password" type="password" class="form-control" name="password" placeholder="Пароль">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </fieldset>

                    <fieldset class="form-group">
                        <div class="col-xs-6">
                            <div class="checkbox" style="padding-top: 0">
                                <label>
                                    <input type="checkbox" name="remember"> Запомнить
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <a href="{{ url('/password/reset') }}">Забыли пароль?</a>
                        </div>
                    </fieldset>

                    <fieldset class="form-group">
                        <div class="col-xs-6 vcenter">
                            <button type="submit" class="btn btn-primary btn-block">
                                Войти
                            </button>
                        </div><!--
                    --><div class="col-xs-6 vcenter">
                            <a href="{{ url('/register') }}">Регистрация</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection