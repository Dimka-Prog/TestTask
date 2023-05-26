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
                    WHERE Rooms.RoomNum NOT IN(
                        SELECT RoomNum
                        FROM Bookings
                        WHERE (('$setDate' <= Bookings.SetDate OR '$setDate' >= Bookings.SetDate) AND '$setDate' < Bookings.DepartureDate)
                              AND
                              (('$departureDate' <= Bookings.DepartureDate OR '$departureDate' >= Bookings.DepartureDate) AND '$departureDate' > Bookings.SetDate)
                    )
                    GROUP BY RoomType.TypeID
                    ORDER BY Price
            ");
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
}