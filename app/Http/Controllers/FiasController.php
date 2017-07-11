<?php

namespace Vombat\Http\Controllers;

use SoapClient;
use SoapFault;

class FiasController extends Controller
{
    public function getFileInfo()
    {
        try {
            // Для общения со службой получения обновлений ФИАС используется протокол SOAP
            $fiasDownloadServiceUrl = config('fias.download_service_url');
            $fiasDownloadService = new SoapClient($fiasDownloadServiceUrl);
            $fiasDeltaXmlUrl = $fiasDownloadService->GetLastDownloadFileInfo()->GetLastDownloadFileInfoResult->FiasDeltaXmlUrl;

            // Для получения файла обновлений используется cURL.
            // Функцию file_get_contents() решено не использовать, т.к. получение данных через неё происходит медленнее,
            // и она не всегда работает из-за настроек безопасности исполняющего скрипт сервера.
            $ch = curl_init($fiasDeltaXmlUrl);          // Создание нового ресурса cURL
            $fp = fopen('archive.rar', 'wb');           // Создание файла-назначения
            curl_setopt($ch, CURLOPT_FILE, $fp);        // Установка необходимых параметров
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_exec($ch);                             // Загрузка файла и его сохранение в файле-назначении
            curl_close($ch);                            // Завершение сеанса и освобождение ресурсов
            fclose($fp);                                // Закрытие файла-назначения
        } catch(SoapFault $exception) {
            echo $exception->getMessage();
        }
    }
}
