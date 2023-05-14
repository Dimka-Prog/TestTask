<?php
namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PDO;

class GetClientData extends Command
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('stepone')
            ->setDescription('Команда, выводящая все бронирования гостя по его номеру паспорта')
            ->addArgument('PassportNum', InputArgument::REQUIRED, 'Номер паспорта гостя');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arguments = $input->getArguments();
        $GuestPassportNum = (int)$arguments['PassportNum'];

        // Получение ФИО гостя по его номеру паспорта
        $sth = $this->db->query("
                SELECT FIO
                FROM Bookings
                    JOIN Guests ON Bookings.PassportNum = Guests.PassportNum
                WHERE Guests.PassportNum = $GuestPassportNum
        ");
        $guest = $sth->fetch(PDO::FETCH_ASSOC);

        if($guest['FIO'])
        {
            $output->writeln("Все бронирования гостя {$guest['FIO']}:");

            // Запрос на получение всех бронирований гостя по его номеру паспорта
            $sth = $this->db->query("
                    SELECT 
                        SetDate, 
                        DepartureDate,
                        RoomNum
                    FROM Bookings
                    WHERE Bookings.PassportNum = $GuestPassportNum
            ");
            $bookings = $sth->fetchAll(PDO::FETCH_ASSOC);

            foreach ($bookings as $booking) {
                $output->writeln("
                    Дата заселения: {$booking['SetDate']}
                    Дата выселения: {$booking['DepartureDate']}
                    Номер комнаты: {$booking['RoomNum']}
                ");
            }
        }
        else
            $output->writeln("Гость с номером паспорта '$GuestPassportNum' еще не бронировал номера!");

        return Command::SUCCESS;
    }
}