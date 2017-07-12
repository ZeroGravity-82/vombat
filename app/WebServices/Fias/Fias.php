<?php

namespace Vombat\WebServices\Fias;

use stdClass;
use SoapClient;
use SoapFault;

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

    public function getAllDownloadFileInfo(): stdClass
    {
        return $this->getDownloadFileInfo(TRUE);
    }


    public function getLastDownloadFileInfo(): stdClass
    {
        return $this->getDownloadFileInfo(FALSE);
    }

    public function getDownloadFileInfo(bool $allDownload = TRUE): stdClass
    {
        try {
            // Для общения со службой получения обновлений ФИАС используется протокол SOAP
            $fiasDownloadServiceUrl = config('fias.download_service_url');
            $fiasDownloadService = new SoapClient($fiasDownloadServiceUrl);

            // Служба предоставляет два метода:
            // - GetAllDownloadFileInfo - возвращает информацию о всех версиях файлов, доступных для скачивания;
            // - GetLastDownloadFileInfo - возвращает информацию о последней версии файлов, доступных для скачивания.
            $methodName = 'GetLastDownloadFileInfo';
            if($allDownload) {
                $methodName = 'GetAllDownloadFileInfo';
            }
            return $fiasDownloadService->$methodName();
        } catch(SoapFault $exception) {
            echo $exception->getMessage();
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