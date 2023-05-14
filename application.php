<?php
require 'vendor/autoload.php';

use DMS\MySQL\DatabaseHotel;
use Symfony\Component\Console\Application;
use Commands\GetClientData;

// Создание и настройка подключения к базе данных
$dms = 'mysql';
$servername = 'localhost';
$dbname = 'Hotel';
$username = 'dimkaprog';
$password = '123456789';
$dsn = "$dms:host=$servername;dbname=$dbname";

try {
    $db = new PDO($dsn, $username, $password);
}
catch (PDOException $PDOException)
{
    $mysqlDB = new DatabaseHotel($servername, $username, $password);
    $mysqlDB->creatDB('databases/MySQL/mysql.dbHotel.sql');
}

$db = new PDO($dsn, $username, $password);

// Создание экземпляра приложения Symfony Console
$application = new Application();

$application->add(new GetClientData($db));

$application->run();
