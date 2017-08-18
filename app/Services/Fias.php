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
     * Требуется проверить наличие доступных для скачивания файлов обновлений ФИАС.
     */
    private const STATUS_CHECK_DEMAND = 'check_demand';

    /**
     * Требуется скачать доступные файлы обновлений ФИАС.
     */
    private const STATUS_DOWNLOAD_DEMAND = 'download_demand';

    /**
     * Требуется установить скачанные файлы обновлений ФИАС.
     */
    private const STATUS_INSTALL_DEMAND = 'install_demand';

    /**
     * База адресов актуальна.
     */
    private const STATUS_UP_TO_DATE = 'up_to_date';

    private $webService;
    private $settingStore;
    private $lastAvailableUpdateInfo ;
    private $lastDownloadedUpdateInfo;

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

    public function isUpToDate()
    {
        return $this->settingStore->get('fias.status') === self::STATUS_UP_TO_DATE;
    }

    public function hasUpdatesToInstall()
    {
        return $this->settingStore->get('fias.status') === self::STATUS_INSTALL_DEMAND;
    }

    public function firstRun()
    {
        return false;
    }
}