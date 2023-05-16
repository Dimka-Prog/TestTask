<?php

namespace Model;

use  PDO;

class HotelModel
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Возвращает ФИО гостя по его номер паспорта, если он хоть раз бронировал номер
    public function getBookingFIO($guestPassportNum)
    {
        $sth = $this->db->query("
                SELECT FIO
                FROM Guests
                    JOIN Bookings ON Bookings.PassportNum = Guests.PassportNum
                WHERE Guests.PassportNum = $guestPassportNum
        ");
        return $sth->fetch(PDO::FETCH_OBJ);
    }

    // Возвращает все бронирования гостя по его номеру паспорта
    public function getGuestBookings($guestPassportNum)
    {
        $sth = $this->db->query("
                    SELECT 
                        SetDate, 
                        DepartureDate,
                        RoomNum
                    FROM Bookings
                    WHERE Bookings.PassportNum = $guestPassportNum
            ");
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }

    // Возвращает информацию о госте по его номеру паспорта
    public function getGuest($guestPassportNum)
    {
        $sth = $this->db->query("
                    SELECT * FROM Guests
                    WHERE Guests.PassportNum = $guestPassportNum
            ");
        return $sth->fetch(PDO::FETCH_OBJ);
    }

    // Возвращает все свободные типы номеров и их стоимость в указанный промежуток дат
    public function getRoomsTypes($setDate, $departureDate)
    {
        $sth = $this->db->query("
                    SELECT
                        RoomType,
                        Price
                    FROM RoomType
                        JOIN Rooms ON Rooms.TypeID = RoomType.TypeID
                    WHERE Rooms.TypeID NOT IN(
                        SELECT TypeID
                        FROM Bookings
                            JOIN Rooms ON Rooms.RoomNum = Bookings.RoomNum
                        WHERE (('$setDate' <= Bookings.SetDate AND '$setDate' <= Bookings.DepartureDate) OR
                               ('$setDate'  >= Bookings.SetDate AND '$setDate' <= Bookings.DepartureDate))
                              AND
                              (('$departureDate' > Bookings.SetDate AND '$departureDate' <= Bookings.DepartureDate) OR
                               ('$departureDate' > Bookings.SetDate AND '$departureDate' >= Bookings.DepartureDate))
                    )
                    GROUP BY Price, RoomType
                    ORDER BY Price
            ");
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }

    // Возвращает количества выходных дней в указанный промежуток дат
    public function getCountWeekends($setDate, $departureDate)
    {
        $sth = $this->db->query("                
                SELECT getCountWeekends('$setDate', '$departureDate') AS CountWeekends
        ");
        return $sth->fetch(PDO::FETCH_OBJ);
    }

    // Возвращает количества дней в указанный промежуток дат
    public function getCountDays($setDate, $departureDate)
    {
        $sth = $this->db->query("                
                SELECT getCountDays('$setDate', '$departureDate') AS CountDays
        ");
        return $sth->fetch(PDO::FETCH_OBJ);
    }
}