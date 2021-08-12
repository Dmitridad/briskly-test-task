<?php

namespace XlsExchanger\Export;

use XlsExchanger\Log\Logger;

class Ftp
{
    protected string $host;
    protected int $port = 21;
    protected string $login;
    protected string $password;
    protected string $dir;
    protected $connection;

    public function __construct($host, $port, $login, $password, $dir)
    {
        $this->host = $host;
        $this->port = (!empty($port)) ? $port : 21;
        $this->login = $login;
        $this->password = $password;
        $this->dir = $dir;
    }

    /**
     * Загружает файл по FTP на сервер
     *
     * @param  string $pathToFile
     * @return bool
     */
    public function uploadFile(string $pathToFile): bool
    {
        if ($this->isConnected()) {
            ftp_pasv($this->connection, true);
            $upload = ftp_put($this->connection, $this->dir, $pathToFile, FTP_BINARY);
            ftp_close($this->connection);

            if ($upload) {
                return true;
            } else {
                Logger::log(Logger::ERROR, 'Загрузка файла по FTP не удалась.', true);

                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Проверяет соединение и вход по FTP
     *
     * @return bool
     */
    private function isConnected(): bool
    {
        $connection = ftp_connect($this->host, $this->port);

        if ($connection) {
            $ftpLogin = ftp_login($connection, $this->login, $this->password);

            if ($ftpLogin) {
                $this->connection = $connection;

                return true;
            } else {
                Logger::log(Logger::ERROR, 'Не удалось выполнить вход по FTP под предоставленными данными.', true);
                ftp_close($connection);

                return false;
            }
        } else {
            Logger::log(Logger::ERROR, 'Не удалось установить соединение по FTP.', true);

            return false;
        }
    }
}
