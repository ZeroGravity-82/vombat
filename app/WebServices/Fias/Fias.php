<?php

namespace Vombat\WebServices\Fias;

use stdClass;
use SoapClient;
use SoapFault;
use Vombat\Exceptions\FiasException;
use Vombat\FiasUpdate;

class Fias
{
    /*
     |-------------------------------------------------------------------------
     | Fias
     |-------------------------------------------------------------------------
     |
     | Реализует взаимодействие со службой получения обновлений ФИАС.
     |
     | Решает следующие задачи:
     | 1.
     | 2.
     | 3.
     |
     */

    public function noDownloadedUpdates(): bool
    {
        $downloadedUpdateList = FiasUpdate::all()->where('downloaded', true);
        return $downloadedUpdateList->isEmpty();
    }


    /**
     * Подключается к службе обновлений ФИАС и проверяет наличие доступных для загрузки файлов обновлений.
     *
     */
    public function checkForAvailableUpdates()
    {

        // Подключиться к службе обновление ФИАС и проверить, появились ли доступные для загрузки файлы обновлений

        $lastAvailableUpdateInfo = $this->getLastAvailableUpdateInfo();
        $lastAvailableUpdateVersionId = $lastAvailableUpdateInfo->VersionId;

        // Если не удалось подключиться - выводим сообщение о проблеме.


        //      2. Проверить в своей БД последнее загруженное обновление
        $lastDownloadedUpdateVersionId = FiasUpdate::first();


    }

    /**
     * Возвращает объект с информацией о последней версии файлов ФИАС, доступной для скачивания.
     *
     * @return stdClass
     */
    public function getLastAvailableUpdateInfo(): stdClass
    {
        return $this->getAvailableUpdateInfo(FALSE)->GetLastDownloadFileInfoResult;
    }

    /**
     * Возвращает массив объектов stdClass с информацией о всех версиях файлов ФИАС, доступных для скачивания.
     *
     * @return array
     */
    public function getAllAvailableUpdateInfo(): array
    {
        return $this->getAvailableUpdateInfo(TRUE)->GetAllDownloadFileInfoResult->DownloadFileInfo;
    }

    /**
     *
     *
     * @param bool $allUpdatesInfo
     * @return stdClass
     * @throws FiasException Если не удалось подключиться к службе получения обновлений ФИАС
     */
    public function getAvailableUpdateInfo(bool $allUpdatesInfo = TRUE): stdClass
    {
        try {
            // Для общения со службой получения обновлений ФИАС используется протокол SOAP
            $fiasDownloadServiceUrl = config('fias.download_service_url');
            $fiasDownloadService = new SoapClient($fiasDownloadServiceUrl);

            // Служба предоставляет два метода:
            // - GetLastDownloadFileInfo - возвращает информацию о последней версии файлов, доступных для скачивания.
            // - GetAllDownloadFileInfo - возвращает информацию о всех версиях файлов, доступных для скачивания;
            $methodName = 'GetLastDownloadFileInfo';
            if($allUpdatesInfo) {
                $methodName = 'GetAllDownloadFileInfo';
            }
            return $fiasDownloadService->$methodName();
        } catch(SoapFault $exception) {
            throw FiasException::FiasConnectionFailed();
        }
    }

    private function downloadFile(string $FileUrl): void
    {
        // Для получения файла обновлений используется cURL.
        // Функцию file_get_contents() решено не использовать, т.к. получение данных через неё происходит медленнее,
        // и она не всегда работает из-за настроек безопасности исполняющего скрипт сервера.
        $ch = curl_init($FileUrl);          // Создание нового ресурса cURL
        $fp = fopen('archive.rar', 'wb');           // Создание файла-назначения
        curl_setopt($ch, CURLOPT_FILE, $fp);        // Установка необходимых параметров
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);                             // Загрузка файла и его сохранение в файле-назначении
        curl_close($ch);                            // Завершение сеанса и освобождение ресурсов
        fclose($fp);                                // Закрытие файла-назначения
    }


    public function showAllCities()
    {

    }
}