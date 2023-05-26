DROP DATABASE IF EXISTS Hotel;
CREATE DATABASE Hotel;
USE Hotel;


CREATE TABLE RoomType (
    TypeID INT UNSIGNED NOT NULL,
    RoomType TEXT(100) NOT NULL,
    Price INT NOT NULL,
    PRIMARY KEY (TypeID),
    INDEX idxTypeID (TypeID)
);


CREATE TABLE HotelStaff (
    StaffID INT UNSIGNED NOT NULL,
    FIO VARCHAR(150) NOT NULL,
    Post VARCHAR(80) NOT NULL,
    Salary INT UNSIGNED NOT NULL,
    WorkSchedule VARCHAR(5) NOT NULL,
    PRIMARY KEY (StaffID),
    INDEX idxStaffID (StaffID)
);


CREATE TABLE Rooms (
    RoomNum INT UNSIGNED NOT NULL,
    Places INT UNSIGNED NOT NULL,
    RoomFeatures MEDIUMTEXT NULL,
    Floor INT UNSIGNED NOT NULL,
    TypeID INT UNSIGNED NOT NULL,
    StaffID INT UNSIGNED NULL,
    RoomStatus VARCHAR(45) NOT NULL,
    PRIMARY KEY (RoomNum),
    CONSTRAINT `fk_Rooms_RoomType1`
     FOREIGN KEY (TypeID)
         REFERENCES RoomType (TypeID)
         ON DELETE CASCADE
         ON UPDATE CASCADE,
    CONSTRAINT fk_Rooms_HotelStaff1
     FOREIGN KEY (StaffID)
         REFERENCES HotelStaff (StaffID)
         ON DELETE CASCADE
         ON UPDATE CASCADE,
    INDEX idxRoomNum (RoomNum),
    INDEX idxTypeID (TypeID),
    INDEX idxStaffID (StaffID)
);


CREATE TABLE Guests (
    PassportNum INT UNSIGNED NOT NULL,
    FIO VARCHAR(150) NOT NULL,
    Citizenship VARCHAR(45) NOT NULL,
    TypeGuest VARCHAR(45) NOT NULL,
    Discount INT NULL,
    PRIMARY KEY (PassportNum),
    INDEX idxPassportNum (PassportNum)
);


CREATE TABLE Placement (
    RoomNum INT UNSIGNED NOT NULL,
    PassportNum INT UNSIGNED NOT NULL,
    SetDate DATETIME NOT NULL,
    DepartureDate DATETIME NULL,
    PRIMARY KEY (RoomNum, PassportNum),
    CONSTRAINT `fk_SettlingRoom_Rooms`
     FOREIGN KEY (RoomNum)
         REFERENCES Rooms (RoomNum)
         ON DELETE CASCADE
         ON UPDATE CASCADE,
    CONSTRAINT `fk_SettlingRoom_Guests1`
     FOREIGN KEY (PassportNum)
         REFERENCES Guests (PassportNum)
         ON DELETE CASCADE
         ON UPDATE CASCADE,
    INDEX idxRoomNum (RoomNum),
    INDEX idxPassportNum (PassportNum)
);


CREATE TABLE Bookings (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    RoomNum INT UNSIGNED NOT NULL,
    PassportNum INT UNSIGNED NOT NULL,
    SetDate DATE NOT NULL,
    DepartureDate DATE NOT NULL,
    FOREIGN KEY (RoomNum)
        REFERENCES Rooms (RoomNum)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (PassportNum)
        REFERENCES Guests (PassportNum)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    INDEX idxRoomNum (RoomNum),
    INDEX idxPassportNum (PassportNum)
);


CREATE TABLE DailyAccounting (
    RoomNum INT UNSIGNED NOT NULL,
    ServiceDate DATETIME NOT NULL,
    ConditionRoom VARCHAR(45) NOT NULL, #Состояние комнаты
    Complaints MEDIUMTEXT NULL, #Жалобы
    ServicesRendered MEDIUMTEXT NOT NULL, #Оказанные услуги
    PRIMARY KEY (RoomNum),
    CONSTRAINT `fk_DailyAccounting_Rooms1`
       FOREIGN KEY (RoomNum)
           REFERENCES Rooms (RoomNum)
           ON DELETE CASCADE
           ON UPDATE CASCADE,
    INDEX idxRoomNum (RoomNum)
);

INSERT RoomType (TypeID, RoomType, Price)
VALUES
    (1100, 'Однокомнатный', 4400),
    (2200, 'Двухкомнатный', 5700),
    (5601, 'Люкс', 6600),
    (6301, 'Апартамент', 7400),
    (2802, 'Семейный', 9800),
    (3000, 'Сюит', 11200);

INSERT HotelStaff (StaffID, FIO, Post, Salary, WorkSchedule)
VALUES
    (1346, 'Фролова Милана Максимовна', 'Горничная', 25000, '5/2'),
    (3401, 'Соболев Илья Максимович', 'Электрик', 40000, '5/2'),
    (2678, 'Баранова Ева Ивановна', 'Горничная', 23000, '2/2'),
    (7118, 'Коновалов Иван Миронович', 'Сантехник', 43000, '5/2'),
    (3256, 'Козина Анна Романовна', 'Горничная', 27000, '3/1'),
    (8693, 'Щербаков Тимур Иванович', 'Сантехник', 41000, '2/2'),
    (5947, 'Родин Илья Александрович', 'Электрик', 45000, '3/1'),
    (6891, 'Мальцев Михаил Артёмович', 'Электрик', 38000, '2/2'),
    (9600, 'Артемов Александр Игоревич', 'Сантехник', 47000, '3/1');

INSERT Rooms (RoomNum, Places, RoomFeatures, Floor, TypeID, StaffID, RoomStatus)
VALUES
    (145, 2, null, 1, 1100, null, 'свободно'),
    (201, 3, null, 2, 2200, null, 'свободно'),
    (426, 2, 'Кабинет, телевизор', 4, 5601, null, 'занято'),
    (351, 4, 'Телефизор, зона для игр', 3, 2802, 5947, 'обслуживается'),
    (457, 3, 'Кухня, кабинет, телевизор', 4, 6301, null, 'занято'),
    (505, 6, 'Гостинная, кабинет, телевизор, проводной интернет', 5, 3000, null, 'свободно'),
    (137, 4, null, 1, 2200, 2678, 'обслуживается'),
    (309, 2, 'Кабинет, телевизор', 4, 5601, null, 'свободно'),
    (346, 4, 'Телефизор, зона для игр', 3, 2802, null, 'свободно'),
    (161, 2, null, 1, 1100, 9600, 'обслуживается');

INSERT Guests (PassportNum, FIO, Citizenship, TypeGuest, Discount)
VALUES
    (866743, 'Максимова Агата Никитична', 'Россия', 'Обычный', null),
    (491364, 'Семенов Макар Дмитриевич', 'Норвегия', 'Обычный', null),
    (310846, 'Черкасов Захар Матвеевич', 'Индий', 'Постоянный', 800),
    (738952, 'Лукьянова Стефания Дмитриевна', 'Греция', ' VIP', 1300),
    (169003, 'Иванова Ева Львовна', 'Македония', 'Постоянный, VIP', 1500);

INSERT Placement (RoomNum, PassportNum, SetDate, DepartureDate)
VALUES
    (201, 866743, '2022-01-27 16:23:56', null),
    (145, 491364, '2022-03-13 10:01:34', '2022-03-14 09:14:08'),
    (505, 169003, '2022-02-08 13:22:11', null),
    (346, 738952, '2022-03-17 17:11:29', '2022-03-20 17:00:21'),
    (309, 310846, '2022-01-04 06:45:57', null);

INSERT Bookings (RoomNum, PassportNum, SetDate, DepartureDate)
VALUES
    (201, 866743, '2023-05-02', '2023-05-04'),
    (145, 491364, '2023-05-05', '2022-05-08'),
    (426, 169003, '2023-05-05', '2023-05-12'),
    (457, 866743, '2023-05-08', '2023-05-20'),
    (505, 310846, '2023-05-10', '2023-05-23');

INSERT DailyAccounting (RoomNum, ServiceDate, ConditionRoom, Complaints, ServicesRendered)
VALUES
    (351, '2022-03-18 18:13:41', 'обслуживается', 'Грязно, протекает труба в ванной', 'Убрано, починка'),
    (137, '2022-03-21 20:43;12', 'обслуживается', 'Не работает кондиционер', 'Починка'),
    (161, '2022-03-19 09:03:22', 'обслуживается', 'Грязно', 'Уборка');