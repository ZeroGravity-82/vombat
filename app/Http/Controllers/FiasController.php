<?php

namespace Vombat\Http\Controllers;

use Vombat\WebServices\Fias\Fias;

class FiasController extends Controller
{
    private $fias;

    public function __construct(Fias $fias)
    {
        $this->fias = $fias;
    }

    /**
     * Показывает Центр обновления адресов Вомбат.
     *
     * ??? Здесь должен отображаться список отслеживаемых городов (название, дата последнего обновления).
     * ??? Получить из своей БД список отслеживаемых городов и дату последнего обновления для каждого из них.
     * ??? Показать рядом с каждым из отслеживаемых городов признак того, что для него есть обновления (информация об
     * ??? этих обновлениях была получена при ручной проверке наличия обновлений или при проверке по расписанию).
     *
     * Способы установки обновлений:
     * 1. Устанавливать обновления автоматически (рекомендуется).
     * 2. Загружать обновления, но решение об установке принимается мной.
     * 3. Искать обновления, но решение о загрузке и установке принимается мной.
     * 4. Не проверять наличие обновлений (не рекомендуется).
     *
     * @return ???
     */
    public function index()
    {
        return view('fias')->with([
            'last_updates_check' => '1982',
            'last_updates_install' => '2017',
            ]);



        // Проверяется наличие доступных для загрузки файлов обновлений
        //$this->fias->checkForAvailableUpdates();   // Должна обновлять инфорамацию о количестве доступных файлов
        //                                           // скачивания и их суммарном размере.
    }
}