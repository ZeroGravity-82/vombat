@extends('layouts.settings')

@section('settings')
<div class="panel panel-default">
    <div class="panel-heading">Данные профиля</div>
    <div class="panel-body">
        <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <p>Картинка профиля</p>
                {{-- TODO: Убрать стили в css-файл --}}
                <div style="width:100px;height:100px;border:1px solid lightgray" id="avatar-container">
                    <img style="width: 100%; height: 100%" src="{{ $avatar_filename }}">
                </div>
                <input type="file" id="avatar" name="avatar">
            </div>
            <div class="form-group">
                <label for="full_name" class="control-label">Полное имя</label>
                <input class="form-control" type="text" name="full_name"
                       value="{{ $full_name }}">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Пол</label>
                <br>
                <label class="form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="0" {{ $gender['female'] }}> Женский
                </label>
                <label class="form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="1" {{ $gender['male'] }}> Мужской
                </label>
            </div>
            <div class="form-group">
                <label for="status_message" class="control-label">Статус</label>
                <input class="form-control" type="text" name="status_message"
                       value="{{ $status_message }}">
            </div>

            {{--TODO Сделать выбор даты как в форме регистрации VK.COM --}}
            <div class="form-group">
                <label for="birth_day" class="control-label">Дата рождения</label>
                <input class="form-control" type="date" name="birth_day" value="{{ $birth_day }}">
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Обновить профиль</button>
            </div>
        </form>
    </div>
</div>
@endsection

<script defer src="/js/avatar_preview.js"></script>