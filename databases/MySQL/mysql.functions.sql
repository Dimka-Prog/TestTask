USE Hotel;


CREATE FUNCTION getCountWeekends(SetDate DATE, DepartureDate DATE) RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE currentDate DATE;
    DECLARE countWeekends INT;

    SET currentDate = SetDate;
    SET countWeekends = 0;

    WHILE currentDate <= DepartureDate DO
            IF DAYOFWEEK(currentDate) = 1 OR DAYOFWEEK(currentDate) = 7 THEN
                SET countWeekends = countWeekends + 1;
            END IF;

            SET currentDate = currentDate + INTERVAL 1 DAY;
        END WHILE;

    RETURN countWeekends;
END;


CREATE FUNCTION getCountDays(SetDate DATE, DepartureDate DATE) RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE currentDate DATE;
    DECLARE countDays INT;

    SET currentDate = SetDate;
    SET countDays = 0;

    WHILE currentDate < DepartureDate DO
            SET countDays = countDays + 1;
            SET currentDate = currentDate + INTERVAL 1 DAY;
        END WHILE;

    RETURN countDays;
END;