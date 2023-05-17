USE Hotel;


CREATE FUNCTION getCountWeekends(SetDate DATE, DepartureDate DATE) RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE countWeekends INT;

    SET countWeekends = (TIMESTAMPDIFF(DAY, SetDate, DepartureDate) DIV 7) * 2 -- Количество полных недель умножаем на 2
        + IF(DAYOFWEEK(SetDate) = 1, 1, 0) -- Учитываем начальную дату, если это воскресенье
        + IF(DAYOFWEEK(DepartureDate) = 7, 1, 0) -- Учитываем конечную дату, если это суббота
        + IF(DAYOFWEEK(SetDate) > DAYOFWEEK(DepartureDate), 2, 0); -- Увеличиваем на 2, если начальная дата позже конечной

    RETURN countWeekends;
END;