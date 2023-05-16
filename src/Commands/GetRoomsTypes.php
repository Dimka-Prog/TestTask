<?php
namespace Commands;

use Model\HotelModel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PDO;

class GetRoomsTypes extends Command
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
            ->setName('rtypes')
            ->setDescription('Команда, выводящая все типы номеров и их стоимость, которые может забронировать гость')
            ->addArgument('SetDate', InputArgument::REQUIRED, 'Планируемая дата заселения')
            ->addArgument('DepartureDate', InputArgument::REQUIRED, 'Планируемая дата выселения')
            ->addArgument('PassportNum', InputArgument::REQUIRED, 'Номер паспорта гостя');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hotel = new HotelModel($this->db);

        $arguments = $input->getArguments();

        $setDate = (string)$arguments['SetDate'];
        $departureDate = (string)$arguments['DepartureDate'];
        $passportNum = (int)$arguments['PassportNum'];

        $weekends = (int)$hotel->getCountWeekends($setDate, $departureDate)->CountWeekends;
        $days = (int)$hotel->getCountDays($setDate, $departureDate)->CountDays;
        $guest = $hotel->getGuest($passportNum);

        $discount = (int)$guest->Discount * $weekends;
        $output->writeln("Свободные типы номеров с указанной стоимостью за $days дней с учетом скидки($discount): ");

        $roomsTypes = $hotel->getRoomsTypes($setDate, $departureDate);
        foreach ($roomsTypes as $roomType) {
            $price = ((int)$roomType->Price * $days) - ((int)$guest->Discount * $weekends);
            $output->writeln("
                    Тип комнаты: $roomType->RoomType
                    Цена: $price
            ");
        }

        return Command::SUCCESS;
    }
}