<?php

namespace XlsExchanger\Handlers;

use XlsExchanger\Log\Logger;

class UploadHandler
{
    private const UPLOAD_DIR = '/tmp/json/';

    private string $filePath;
    private bool $isSuccess = false;
    
    /**
     * Сохранение входного файла локально и возврат массива параметров экспорта
     *
     * @return array
     */
    public function exec(): array
    {
        $this->setFilePath();
        $this->save();

        return $this->getExportParams();
    }

        
    /**
     * Установка пути и названия для входного файла
     *
     * @return void
     */
    private function setFilePath(): void
    {
        $this->filePath = self::UPLOAD_DIR . basename($_FILES['file_orders']['name']);
    }

        
    /**
     * Сохранение входного файла локально
     *
     * @return void
     */
    private function save(): void
    {
        if (move_uploaded_file($_FILES['file_orders']['tmp_name'], $this->filePath)) {
            $this->isSuccess = true;
            Logger::log(Logger::INFO, 'Входной JSON файл корректен и был успешно загружен.', true);
        } else {
            $this->isSuccess = false;
            Logger::log(Logger::ERROR, 'Входной JSON файл не был загружен.', true);
        }
    }
    
    /**
     * Получение массива с обработанными параметрами экспорта
     *
     * @return array
     */
    private function getExportParams(): array
    {
        if ($this->isSuccess) {
            return $this->handleExportParams();
        } else {
            return [];
        }
    }

    /**
     * Обработка входного массива с параметрами экспорта
     *
     * @return array
     */
    private function handleExportParams(): array
    {
        if ($_POST['export_type']) {

            $exportParams = [];
            switch ($_POST['export_type']) {
                case 'local':
                    $exportParams = [
                        'input_file_path' => $this->filePath,
                        'output_file_name' => $_POST['file_name'] ?: 'default',
                        'type' => 'local',
                    ];
                    break;
                case 'ftp':
                    $exportParams = [
                        'input_file_path' => $this->filePath,
                        'output_file_name' => $_POST['file_name'] ?: 'default',
                        'type' => 'ftp',
                        'ftp_params' => [
                            'host' => $_POST['ftp_host'],
                            'port' => $_POST['ftp_port'],
                            'login' => $_POST['ftp_login'],
                            'password' => $_POST['ftp_password'],
                            'dir' => $_POST['ftp_dir']
                        ]
                    ];
                    break;
            }

            return $exportParams;
        } else {
            return [];
        }
    }
}
