<?php

namespace DMS;

use mysqli;

class Database
{
    private $conn;
    private $dms;

    public function __construct($dms, $servername, $username, $password)
    {
        $this->dms = $dms;
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
            echo "Соединение с $this->dms установлено!" . PHP_EOL;
    }

    public function creatDB($sqlFile)
    {
        // Чтение содержимого SQL-файла
        $sql = file_get_contents($sqlFile);

        if ($this->conn->multi_query($sql) === TRUE) {
            echo "SQL-скрипт $sqlFile выполняется..." . PHP_EOL;

            // Пустой цикл, который будет выполняться пока не выполнит весь sql код
            while ($this->conn->next_result()) {}
            echo "База данных создана!\n\n";
        } else {
            echo $this->conn->error;
        }

        $this->conn->close();
    }
}