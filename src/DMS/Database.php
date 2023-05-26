<?php

namespace DMS;

use mysqli;

class Database
{
    private $conn;

    public function __construct($hostname, $username, $password)
    {
        $this->connect($hostname, $username, $password);
    }

    private function connect($hostname, $username, $password)
    {
        $this->conn = new mysqli($hostname, $username, $password);
        // Проверка соединения
        if ($this->conn->connect_error) {
            die($this->conn->connect_error);
        }
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
        } else
            echo $this->conn->error;
    }

    public function readSQL($sqlFile)
    {
        $sql = file_get_contents($sqlFile);

        if ($this->conn->multi_query($sql) === FALSE)
            echo $this->conn->error;
    }
}