<?php

require_once 'vendor/autoload.php';

use XlsExchanger\Handlers\UploadHandler;
use XlsExchanger\Exchange\XlsExchange;
use XlsExchanger\Log\Logger;

if ($_FILES['file_orders']) {
    $upload = new UploadHandler();
    $exportParams = $upload->exec();

    if (!empty($exportParams)) {
        (new XlsExchange())
            ->setExportParams($exportParams)
            ->export();
    } else {
        Logger::log(Logger::ERROR, 'Не удалось сформировать массив параметров для экспорта.', true);
    }
} else {
    require_once 'core/views/form.php';
}
