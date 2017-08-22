@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <h1 class="card-header">Центр обновления адресов Вомбат</h1>
                <div class="card-block">
                    <div style="border: 1px solid lightgray">
                        @if($fias->)


                        @if($fias->hasUpdatesToDownload())
                            @include('partials.fias.check_for_updates')
                        @endif
                        @if($fias->hasUpdatesToInstall())
                            @include('partials.fias.install_updates')
                        @endif
                        @if($fias->isUpToDate())
                            @include('partials.fias.up-to-date')
                        @endif



                            @include('partials.fias.check_for_updates')
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    {{--<p>Последний поиск обновлений: {{ $last_updates_check }}</p>--}}
                    {{--<p>Обновления устанавливались: {{ $last_updates_install }}</p>--}}

                    <p class="text-muted">Обновления будут загружены и установлены автоматически.</p>
                    <!--
                    <p class="text-muted">Обновления будут загружены автоматически, установка производится вручную.</p>
                    <p class="text-muted">Обновления будут найдены автоматически, загрузка и установка производятся вручную.</p>
                    <p class="text-muted">Проверка наличия обновлений отключена.</p>
                    -->
                    <a href="#" class="card-link">История обновлений</a>
                    <br>
                    <a href="#" class="card-link">Настройка параметров</a>


                    {{--echo 'Группа элементов управления [ИСТОРИЯ]';--}}
                    {{--echo 'Последний поиск обновлений:';--}}
                    {{--echo $this->fias->getTimestampOfLastCheck();                    // Например, 01.08.2017 в 14:35 или Никогда--}}
                    {{--echo 'Обновления устанавливались: ';--}}
                    {{--echo $this->fias->getTimestampOfLastInstall();                  // Например, 31.07.2017 в 07:51 или Никогда--}}
                    {{--echo $this->fias->getSuccessOfLastInstall();                    // Справа от штампа времени последней установки обновления,--}}
                    {{--// например, (не удалось). Если удалось - ничего не выводить.--}}
                    {{--echo 'Доступные обновления будут скачаны и установлены автоматически.'; // Выводится способ установки обновлений--}}

                    {{--echo 'История обновлений';                                      // Гиперссылка справа от штампа времени последней установки обновлений--}}
                    {{--echo 'Группа элементов управления [НАСТРОЙКА ПАРАМЕТРОВ]';      // Гиперссылка для окна настройки центра обновлений адресов Вомбат.--}}

                </div>
            </div>
        </div>
    </div>
@endsection