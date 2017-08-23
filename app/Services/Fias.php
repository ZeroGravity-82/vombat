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
     * При загрузке обновлений возникла ошибка
     */
    private const STATUS_DOWNLOAD_FAILED = 'download_failed';

    /**
     * Обновления загружены
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
    private $lastAvailableUpdateInfo ;
    private $lastDownloadedUpdateInfo;


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
     * Проверяет наличие скачанных файлов обновлений ФИАС.
     *
     * @return bool
     */
    public function noUpdatesDownloaded(): bool
    {
        return FiasUpdate::all()->where('downloaded', true)->isEmpty();
    }

    /**
     * Возвращает количество доступных для загрузки файлов обновлений ФИАС.
     *
     * @return bool
     */
    public function checkForAvailableUpdates(): bool
    {
        try {
            // Проверить ID последнего доступного файла обновлений ФИАС, возвращаемого службой обновлений ФИАС
            $this->lastAvailableUpdateInfo = $this->webService->getLastAvailableUpdateInfo();

            // Проверить ID последнего загруженного файла обновлений ФИАС, содержащегося в базе данных приложения
            $this->lastDownloadedUpdateInfo = FiasUpdate::where('downloaded', true)->latest()->first();

            // Если ID различаются, значит есть доступные для загрузки файлы обновлений ФИАС, необходимо их
            // сосчитать.
            $lastAvailableUpdateVersionId = $this->lastAvailableUpdateInfo->VersionId;
            if(isset($this->lastDownloadedUpdateInfo)) {
                $lastDownloadedUpdateVersionId = $this->lastDownloadedUpdateInfo->VersionId;
            }
            return $lastAvailableUpdateVersionId <> $lastDownloadedUpdateVersionId;
        }
        catch (FiasException $exception) {
            // TODO: Если не удалось подключиться - выводим сообщение о проблеме.
            dd('Поймал исключение FiasException!');
        }
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