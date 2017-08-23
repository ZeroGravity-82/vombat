<?php

namespace Vombat\Services;

use Vombat\FiasUpdate;
use Vombat\Exceptions\FiasException;
use anlutro\LaravelSettings\SettingStore;

class Fias
{
    /*
     |-------------------------------------------------------------------------
     | Fias
     |-------------------------------------------------------------------------
     |
     | Центр обновления адресов ФИАС.
     |
     */

    /**
     * Начальное состояние - база адресов пуста
     */
    private const STATUS_INITIAL = 'initial';

    /**
     * При поиске обновлений возникла ошибка
     */
    private const STATUS_CHECK_FAILED = 'check_failed';

    /**
     * Найдены обновления, доступные для скачивания
     */
    private const STATUS_UPDATES_FOUND = 'updates_found';

    /**
     * При скачивании обновлений возникла ошибка
     */
    private const STATUS_DOWNLOAD_FAILED = 'download_failed';

    /**
     * Обновления скачаны
     */
    private const STATUS_UPDATES_DOWNLOADED = 'updates_downloaded';

    /**
     * При установке обновлений возникла ошибка
     */
    private const STATUS_INSTALL_FAILED = 'install_failed';

    /**
     * Обновления установлены
     */
    private const STATUS_UPDATES_INSTALLED = 'updates_installed';

    /**
     * Неопределённое состояние - необходимо выполнить поиск обновлений, доступных для скачивания
     */
    private const STATUS_UNCERTAIN = 'uncertain';

    /**
     * База адресов актуальна
     */
    private const STATUS_UP_TO_DATE = 'up_to_date';





    private $webService;
    private $settingStore;


    /**
     * Конструктор.
     * @param FiasWebService $webService
     * @param SettingStore $settingStore
     */
    public function __construct(FiasWebService $webService, SettingStore $settingStore)
    {
        $this->webService = $webService;
        $this->settingStore = $settingStore;
    }







    /**
     * Проверяет наличие скачанных обновлений ФИАС.
     * @return bool
     */
    public function noUpdatesDownloaded(): bool
    {
        return FiasUpdate::all()->where('downloaded', true)->isEmpty();
    }

    /**
     * Проверяет наличие доступных для скачивания обновлений ФИАС.
     * @return bool
     */
    public function hasAvailableUpdates(): bool
    {
        try {
            // Если ID последнего доступного и последнего скачанного обновлений ФИАС различаются, значит есть доступные
            // для скачивания обновления ФИАС.
            $lastAvailableUpdateInfo = $this->webService->getLastAvailableUpdateInfo();
            $lastDownloadedUpdateInfo = FiasUpdate::where('downloaded', true)->latest()->first();
            $lastAvailableUpdateVersionId = $lastAvailableUpdateInfo->VersionId;
            $lastDownloadedUpdateVersionId = isset($lastDownloadedUpdateInfo) ?
                                             $lastDownloadedUpdateInfo->version_id : null;
            return $lastAvailableUpdateVersionId <> $lastDownloadedUpdateVersionId;
        }
        catch (FiasException $exception) {
            // TODO: Если не удалось подключиться - выводим сообщение о проблеме.
            dd('Поймал исключение FiasException!');
        }
    }

    /**
     * Актуализирует информацию о доступных для скачивания обновлениях.
     */
    public function refreshUpdatesInfo()
    {
        $lastKnownUpdateInfo = FiasUpdate::orderBy('version_id', 'desc')->first();
        $lastKnownUpdateVersionId = isset($lastKnownUpdateInfo) ? $lastKnownUpdateInfo->version_id : null;

        $allAvailableUpdatesInfo = $this->webService->getAllAvailableUpdatesInfo();

        $start = microtime(true);



        foreach($allAvailableUpdatesInfo as $availableUpdateInfo) {
            $availableUpdateVersionId = $availableUpdateInfo->VersionId;
            if($availableUpdateVersionId > $lastKnownUpdateVersionId) {
                FiasUpdate::create([
                    'version_id'            => $availableUpdateVersionId,
                    'text_version'          => $availableUpdateInfo->TextVersion,
                    'fias_complete_xml_url' => $availableUpdateInfo->FiasCompleteXmlUrl,
                    'fias_delta_xml_url'    => $availableUpdateInfo->FiasDeltaXmlUrl,
                ]);
            }
        }




        return microtime(true) - $start;

   }

    /**
     * Скачивает все доступные обновления.
     */
    public function downloadAvailableUpdates()
    {
    // TODO: реализовать метод
    }

    public function hasUpdatesToDownload()
    {
        return $this->settingStore->get('fias.status') == self::STATUS_DOWNLOAD_DEMAND;
    }

    public function hasUpdatesToInstall()
    {
        return $this->settingStore->get('fias.status') === self::STATUS_INSTALL_DEMAND;
    }

    public function isUpToDate()
    {
        return $this->settingStore->get('fias.status') === self::STATUS_UP_TO_DATE;
    }

    public function firstRun()
    {
        return false;
    }
}