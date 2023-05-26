<?php
require 'vendor/autoload.php';

use Commands\GetClientData;
use Commands\GetRoomsTypes;
use DMS\Database;
use Symfony\Component\Console\Application;

// Данные для подключения к базе данных
$dms = 'mysql';
$hostname = 'localhost';
$dbname = 'Hotel';
$username = 'dimkaprog';
$password = '123456789';
$dsn = "$dms:host=$hostname;dbname=$dbname";

$db = null;
$mysqlDB = null;
try {
    $mysqlDB = new Database($hostname, $username, $password);
    $db = new PDO($dsn, $username, $password);
}
catch (PDOException $PDOException)
{
    $mysqlDB->creatDB('databases/MySQL/mysql.dbHotel.sql');
    $db = new PDO($dsn, $username, $password);
}

$mysqlDB->readSQL('databases/MySQL/mysql.functions.sql');

// Создание экземпляра приложения Symfony Console
$application = new Application();

$application->addCommands([new GetClientData($db), new GetRoomsTypes($db)]);

$application->run();
