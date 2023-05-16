<?php
namespace Commands;

use Model\HotelModel;
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
        $hotel = new HotelModel($this->db);
        $arguments = $input->getArguments();
        $guestPassportNum = (int)$arguments['PassportNum'];

        $guest = $hotel->getBookingFIO($guestPassportNum);

        if($guest->FIO)
        {
            $output->writeln("Все бронирования гостя $guest->FIO:");

            $bookings = $hotel->getGuestBookings($guestPassportNum);
            foreach ($bookings as $booking) {
                $output->writeln("
                    Дата заселения: $booking->SetDate
                    Дата выселения: $booking->DepartureDate
                    Номер комнаты: $booking->RoomNum
                ");
            }
        }
        else
            $output->writeln("Гость с номером паспорта '$guestPassportNum' еще не бронировал номера!");

        return Command::SUCCESS;
    }
}