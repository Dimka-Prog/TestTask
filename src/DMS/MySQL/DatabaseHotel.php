<?php

namespace DMS\MySQL;

use mysqli;

class DatabaseHotel
{
    private $conn;

    public function __construct($servername, $username, $password)
    {
        $this->connect($servername, $username, $password);
    }

    private function connect($servername, $username, $password)
    {
        $this->conn = new mysqli($servername, $username, $password);
        // Проверка соединения
        if ($this->conn->connect_error) {
            die($this->conn->connect_error);
        }
        else
            echo "Соединение с MySQL установлено!" . PHP_EOL;
    }

    public function creatDB($sqlFile)
    {
        // Чтение содержимого SQL-файла
        $sql = file_get_contents($sqlFile);

        if ($this->conn->multi_query($sql) === TRUE) {
            echo "SQL-скрипт $sqlFile успешно выполнен" . PHP_EOL;
        } else {
            echo $this->conn->error;
        }

        $this->conn->close();
    }
}