<?php
namespace Commands;

use Symfony\Component\Console\Command\Command;
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
            ->setDescription('Команда для получения данных о типах комант отеля');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Получение всех записей из таблицы
        $sth = $this->db->query("SELECT * FROM RoomType");
        $rooms = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rooms as $room) {
            $output->writeln("
                TypeID: {$room['TypeID']} 
                RoomType: {$room['RoomType']} 
                Price: {$room['Price']}
            ");
        }

        return Command::SUCCESS;
    }
}