<?php

namespace Vombat\Services;

use stdClass;
use SoapClient;
use SoapFault;
use Vombat\Exceptions\FiasException;

class FiasWebService
{
    /*
     |-------------------------------------------------------------------------
     | FiasWebService
     |-------------------------------------------------------------------------
     |
     | Реализует взаимодействие со службой получения обновлений ФИАС.
     |
     */

    /**
     * Возвращает объект stdClass с информацией о последней версии обновлений ФИАС, доступной для скачивания.
     * @return stdClass
     */
    public function getLastAvailableUpdateInfo(): stdClass
    {
        return $this->getAvailableUpdatesInfo(FALSE)->GetLastDownloadFileInfoResult;
    }

    /**
     * Возвращает массив объектов stdClass с информацией о всех версиях обновлений ФИАС, доступных для скачивания.
     * @return array
     */
    public function getAllAvailableUpdatesInfo(): array
    {
        return $this->getAvailableUpdatesInfo(TRUE)->GetAllDownloadFileInfoResult->DownloadFileInfo;
    }

    /**
     * Подключается к службе получения обновлений ФИАС, получает информацию и возвращает объект с информацией о
     * последней или о всех версиях обновлений ФИАС, доступных для скачивания.
     * @param bool $allUpdatesInfo
     * @return stdClass
     * @throws FiasException Если не удалось подключиться к службе получения обновлений ФИАС
     */
    public function getAvailableUpdatesInfo(bool $allUpdatesInfo = TRUE): stdClass
    {
        try {
            // Для общения со службой получения обновлений ФИАС используется протокол SOAP
            $fiasDownloadServiceUrl = config('fias.download_service_url');
            $fiasDownloadService = new SoapClient($fiasDownloadServiceUrl);

            // Служба предоставляет два метода:
            // - GetLastDownloadFileInfo - возвращает информацию о последней версии обновлений ФИАС, доступной для
            //                             скачивания.
            // - GetAllDownloadFileInfo  - возвращает информацию о всех версиях обновлений ФИАС, доступных для
            //                             скачивания;
            $methodName = 'GetLastDownloadFileInfo';
            if($allUpdatesInfo) {
                $methodName = 'GetAllDownloadFileInfo';
            }
            return $fiasDownloadService->$methodName();
        } catch(SoapFault $exception) {
            throw FiasException::FiasConnectionFailed();
        }
    }

    // TODO: Сделать описание метода
    /**
     *
     *
     * Для получения файла обновлений используется cURL. Функцию file_get_contents() решено не использовать, т.к.
     * получение данных через неё происходит медленнее, и она не всегда работает из-за настроек безопасности сервера,
     * исполняющего скрипт.
     */
    private function downloadFile(string $FileUrl): void
    {

        $ch = curl_init($FileUrl);          // Создание нового ресурса cURL
        $fp = fopen('archive.rar', 'wb');           // Создание файла-назначения
        curl_setopt($ch, CURLOPT_FILE, $fp);        // Установка необходимых параметров
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);                             // Скачивание файла и его сохранение в файле-назначении
        curl_close($ch);                            // Завершение сеанса и освобождение ресурсов
        fclose($fp);                                // Закрытие файла-назначения
    }
}