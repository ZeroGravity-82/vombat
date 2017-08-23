@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-xs-3">
        <div class="panel panel-default">
            <div class="panel-heading">Настройки</div>
            <nav class="nav nav-pills nav-stacked">
                <li role="presentation" class="{{ $current_route_name == 'profile.edit' ? 'active' : '' }}">
                    <a href="{{ url(route('profile.edit')) }}">Профиль</a>
                </li>
                <li role="presentation" class="{{ $current_route_name == 'account.edit' ? 'active' : '' }}">
                    <a href="{{ url(route('account.edit')) }}">Учётная запись</a>
                </li>
                <li role="presentation" class="{{ $current_route_name == 'emails.edit' ? 'active' : '' }}">
                    <a href="{{ url(route('emails.edit')) }}">Email</a>
                </li>
            </nav>
        </div>
    </div>
    <div class="col-xs-9">
        @yield('user')
    </div>
</div>
@endsection