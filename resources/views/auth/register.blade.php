@extends('layouts.app')

@section('styles')
    @include('partials.tether-css')
@endsection

@section('content')
<div class="row">
    <div class="col-xs-4 col-xs-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">Регистрация</div>
            <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                    {{ csrf_field() }}

                    <fieldset class="form-group">
                        <div class="col-xs-12">
                            <span class="account-content-block__header">Быстрая регистрация через</span>
                            <p>
                                <a href="#" class="social-icon social-icon_link social-icon_vk "></a>
                                <a href="#" class="social-icon social-icon_link social-icon_ok "></a>
                                <a href="#" class="social-icon social-icon_link social-icon_fb "></a>
                                <a href="#" class="social-icon social-icon_link social-icon_gplus "></a>
                            </p>
                        </div>
                    </fieldset>

                    <fieldset class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <div class="col-xs-12">
                            <input id="username" type="text" class="form-control" name="username"
                                   value="{{ old('username') }}"
                                   placeholder="Имя пользователя" autofocus
                                   title="Псевдоним для общения"
                                   data-toggle="tooltip"
                                   data-placement="right">
                            @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </fieldset>

                    <fieldset class="form-group{{ $errors->has('mobile_or_email') ? ' has-error' : '' }}">
                        <div class="col-xs-12">
                            <input id="mobile_or_email" type="text" class="form-control" name="mobile_or_email"
                                   value="{{ old('mobile_or_email') }}" placeholder="Телефон или email"
                                   title="Например, +7-913-123-4567 или max@mail.com"
                                   data-toggle="tooltip"
                                   data-placement="right">
                            @if ($errors->has('mobile_or_email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('mobile_or_email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </fieldset>

                    <fieldset class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <div class="col-xs-12">
                            <input id="password" type="password" class="form-control" name="password"
                                   placeholder="Пароль">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </fieldset>

                    <fieldset class="form-group">
                        <div class="col-xs-8 vcenter">
                            <button type="submit" class="btn btn-primary btn-block">
                                Зарегистрироватьcя
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @include('partials.tether-js')
@endsection