<?php

namespace XlsExchanger\Log;

class Logger
{
    private const PATH_TO_LOG_DIR = '/tmp/logs/';

    private const ERROR_FILE_NAME = 'error.log';
    private const DEBUG_FILE_NAME = 'debug.log';
    private const INFO_FILE_NAME = 'info.log';
    private const NO_VALID_FILE_NAME = 'no_valid.log';

    const ERROR = 'ERROR';
    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const NO_VALID = 'NO_VALID';
    
    /**
     * Производит логирование информации
     *
     * @param  string $logType
     * @param  mixed $data
     * @param  bool $browserOutput
     * @return void
     */
    public static function log(string $logType, $data, bool $browserOutput = false): void
    {
        switch ($logType) {
            case self::DEBUG:
                $logFileName = self::DEBUG_FILE_NAME;
                break;
            case self::ERROR:
                $logFileName = self::ERROR_FILE_NAME;
                break;
            case self::NO_VALID:
                $logFileName = self::NO_VALID_FILE_NAME;
                break;
            default:
                $logFileName = self::INFO_FILE_NAME;
                break;
        }

        $pathToLogFile = self::PATH_TO_LOG_DIR . $logFileName;
        $formattedData = self::getFormattedData($data);
        file_put_contents($pathToLogFile, print_r($formattedData, 1), FILE_APPEND);

        if ($browserOutput) {
            echo nl2br($formattedData);
        }
    }
    
    /**
     * Добавляет к входящим данным временной признак
     *
     * @param  mixed $data
     * @return string
     */
    private static function getFormattedData($data): string
    {
        return '[' . date('Y-m-d H:i:s') . ']' . PHP_EOL . $data . PHP_EOL . PHP_EOL;
    }
}