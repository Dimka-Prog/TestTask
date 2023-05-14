<?php
require 'vendor/autoload.php';

use Commands\GetClientData;
use DMS\Database;
use Symfony\Component\Console\Application;

// Создание и настройка подключения к базе данных
$dms = 'mysql';
$servername = 'localhost';
$dbname = 'Hotel';
$username = 'dimkaprog';
$password = '123456789';
$dsn = "$dms:host=$servername;dbname=$dbname";

$db = null;
try {
    $db = new PDO($dsn, $username, $password);
}
catch (PDOException $PDOException)
{
    $mysqlDB = new Database($dms, $servername, $username, $password);
    $mysqlDB->creatDB('databases/MySQL/mysql.dbHotel.sql');
    $db = new PDO($dsn, $username, $password);
}

// Создание экземпляра приложения Symfony Console
$application = new Application();

$application->add(new GetClientData($db));

$application->run();
