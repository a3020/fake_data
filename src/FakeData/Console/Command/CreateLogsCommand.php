<?php

namespace A3020\FakeData\Console\Command;

use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Logging\Logger;
use Concrete\Core\Support\Facade\Application;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateLogsCommand extends Command
{
    protected $appInstance;
    protected $connection;

    const NUMBER_OF_LOGS = 50;

    protected function configure()
    {
        $this
            ->setName('fake-data:create:logs')
            ->setDescription('Create fake log entries')
            ->addOption('amount', null, InputOption::VALUE_OPTIONAL, 'Number of logs to be created');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appInstance = Application::getFacadeApplication();
        $this->connection = $this->appInstance->make(Connection::class);

        $numberOfLogs = (int) ($input->getOption('amount') ?? self::NUMBER_OF_LOGS);
        $this->createLogs($numberOfLogs);

        $output->writeln('<info>Number of logs created: '. $numberOfLogs .'</info>');
    }

    /**
     * @param int $numberOfLogs
     */
    private function createLogs($numberOfLogs)
    {
        $logger = $this->appInstance->make(Logger::class);
        $faker = Factory::create();

        $allUserIds = $this->getAllUserIds();

        $statement = $this->connection->prepare('
            INSERT INTO Logs (channel, level, message, time, uID)
            VALUES (:channel, :level, :message, :time, :uID)
        ');

        for ($i = 0; $i < $numberOfLogs; $i++) {
            $statement->execute(
                [
                    'channel' => $faker->randomElement($logger->getChannels()),
                    'level' => $faker->randomElement($logger->getLevels()),
                    'message' => $faker->text,
                    'time' => $faker->dateTimeThisYear()->getTimestamp(),
                    'uID' => (int) $faker->randomElement($allUserIds),
                ]
            );
        }
    }

    /**
     * @return int[]
     */
    private function getAllUserIds()
    {
        $rows = $this->connection->fetchAll('SELECT uID FROM Users');

        return array_map(function($row) {
            return (int) $row['uID'];
        }, $rows);
    }
}
