<?php

namespace Model;

use PDO;

class DateHelper
{
    // Возвращает количества выходных дней в указанный промежуток дат
    public static function getCountWeekends(PDO $db, $setDate, $departureDate)
    {
        $sth = $db->query("                
                SELECT getCountWeekends('$setDate', '$departureDate') AS CountWeekends
        ");
        return $sth->fetch(PDO::FETCH_OBJ);
    }

    // Возвращает количества дней в указанный промежуток дат
    public static function getCountDays(PDO $db, $setDate, $departureDate)
    {
        $sth = $db->query("                
                SELECT TIMESTAMPDIFF(DAY, '$setDate', '$departureDate') AS CountDays
        ");
        return $sth->fetch(PDO::FETCH_OBJ);
    }
}