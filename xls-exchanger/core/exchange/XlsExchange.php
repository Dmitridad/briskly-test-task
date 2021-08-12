<?php

namespace XlsExchanger\Exchange;

use XlsExchanger\Exchange\Excel;
use XlsExchanger\Export\Ftp;
use XlsExchanger\Log\Logger;

class XlsExchange
{
    protected const LOCAL_EXPORT_DIR = '/tmp/xls/';

    protected string $exportType;
    protected string $pathToInputJsonFile;
    protected string $pathToOutputXlsxFile;
    protected string $newFileName;
    protected string $ftpHost;
    protected int $ftpPort;
    protected string $ftpLogin;
    protected string $ftpPassword;
    protected string $ftpDir;
        
    /**
     * Устанавливает параметры для экспорта в зависимости от типа экспорта
     *
     * @param  array $exportParams
     * @return XlsExchange
     */
    public function setExportParams(array $exportParams): XlsExchange
    {
        $this->exportType = $exportParams['type'];
        $this->pathToInputJsonFile = $exportParams['input_file_path'];
        $this->newFileName = $exportParams['output_file_name'];
        $this->pathToOutputXlsxFile = self::LOCAL_EXPORT_DIR . $this->newFileName . '_' . strtotime(date('Y-m-d H:i:s')) . '.xlsx';

        if ($this->exportType == 'ftp') {
            $this->ftpHost = $exportParams['ftp_params']['host'];
            $this->ftpPort = $exportParams['ftp_params']['port'];
            $this->ftpLogin = $exportParams['ftp_params']['login'];
            $this->ftpPassword = $exportParams['ftp_params']['password'];
            $this->ftpDir = $exportParams['ftp_params']['dir'] . '/' . $this->newFileName . '.xlsx';
        }

        return $this;
    }

    /**
     * Выполняет экспорт созданного файла
     *
     * @return bool
     */
    public function export(): bool
    {
        $inputJson = file_get_contents($this->pathToInputJsonFile);

        $excel = new Excel();
        $result = $excel->createFromJson($inputJson, $this->pathToOutputXlsxFile);

        if ($result) {
            Logger::log(Logger::INFO, 'Файл XLSX с информацией по заказу успешно создан.', true);

            if ($this->exportType == 'ftp') {
                $ftp = new Ftp(
                    $this->ftpHost,
                    $this->ftpPort,
                    $this->ftpLogin,
                    $this->ftpPassword,
                    $this->ftpDir
                );

                $success = $ftp->uploadFile($this->pathToOutputXlsxFile);

                if ($success) {
                    Logger::log(Logger::INFO, 'Файл XLSX с информацией по заказу успешно загружен по FTP.', true);
                    unlink($this->pathToOutputXlsxFile);
                }
            }

            unlink($this->pathToInputJsonFile);

            return true;
        } else {
            Logger::log(Logger::ERROR, 'Файл XLSX с информацией по заказу не был создан, попробуйте ещё раз.', true);
            
            return false;
        }
    }
}
