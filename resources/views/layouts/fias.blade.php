@extends('layouts.app')

@section('content')

    @yield('updates_check')

    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <h1 class="card-header">Центр обновления адресов Вомбат</h1>
                <div class="card-block">
                    <h2 class="card-title">Состояние обновления</h2>
                    <p class="'card-text">Обновления ещё не устанавливались!</p>
                    <p class="'card-text">Всегда следите за обновлениями.</p>
                    <a href="/" class="btn btn-primary">Проверить наличие обновлений</a>
                </div>
                <div class="card-footer">
                    <p>Последний поиск обновлений: {{ $last_updates_check }}</p>
                    <p>Обновления устанавливались: {{ $last_updates_install }}</p>
                    <a hreh="#" class="card-link">История обновлений</a>

                    <p class="text-muted">Обновления будут загружены и установлены автоматически.</p>
                    <!--
                    <p class="text-muted">Обновления будут загружены автоматически, установка производится вручную.</p>
                    <p class="text-muted">Обновления будут найдены автоматически, загрузка и установка производятся вручную.</p>
                    <p class="text-muted">Проверка наличия обновлений отключена.</p>
                    -->
                    <a hreh="#" class="card-link">Настройка параметров</a>
                </div>
            </div>
        </div>
    </div>
@endsection