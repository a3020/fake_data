<?php

namespace A3020\FakeData\Console\Command;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Entity\User\User;
use Concrete\Core\Support\Facade\Application;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use Hautelook\Phpass\PasswordHash;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUsersCommand extends Command
{
    /** @var  \Concrete\Core\Application\Application */
    protected $appInstance;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var PasswordHash */
    protected $hasher;

    const NUMBER_OF_USERS = 50;

    protected function configure()
    {
        $this
            ->setName('fake-data:create:users')
            ->setDescription('Create fake users')
            ->addOption('amount', null, InputOption::VALUE_OPTIONAL, 'Number of users to be created');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appInstance = Application::getFacadeApplication();
        $this->entityManager = $this->appInstance->make(EntityManagerInterface::class);

        $config = $this->appInstance->make(Repository::class);
        $this->hasher = new PasswordHash($config->get('concrete.user.password.hash_cost_log2'), $config->get('concrete.user.password.hash_portable'));

        $numberOfUsers = (int) ($input->getOption('amount') ?? self::NUMBER_OF_USERS);
        $this->createUsers($numberOfUsers);

        $output->writeln('<info>Number of users created: '. $numberOfUsers .'</info>');
    }

    /**
     * @param int $numberOfUsers
     */
    private function createUsers($numberOfUsers)
    {
        $faker = Factory::create();
        $populator = new Populator($faker, $this->entityManager);

        $populator->addEntity(User::class, $numberOfUsers, [
            'uName' => function() use ($faker) { return $faker->unique()->userName; },
            'uEmail' => function() use ($faker) { return $faker->unique()->email; },
            'uPassword' => function() use ($faker) { return $this->hasher->HashPassword($faker->password()); },
            'uIsActive' => function() use ($faker) { return $faker->boolean; },
            'uIsValidated' => function() use ($faker) { return $faker->boolean; },
            'uDateAdded' => function() use ($faker) { return $faker->dateTimeThisYear(); },
            'uLastLogin' => function() use ($faker) { return $faker->dateTimeThisYear()->getTimestamp(); },
            'uLastIP' => function() use ($faker) { return bin2hex(inet_pton($faker->ipv4)); },
            'uTimezone' => function() use ($faker) { return $faker->timezone; },
            'uDefaultLanguage' => function() use ($faker) { return $faker->locale; },
            'uLastAuthTypeID' => null,
            'uNumLogins' => function() use ($faker) { return $faker->numberBetween(0, 150); },
            'uPreviousLogin' => function() use ($faker) { return $faker->dateTimeThisYear()->getTimestamp(); },
            'uLastOnline' => function() use ($faker) { return $faker->dateTimeThisYear()->getTimestamp(); },
            'uLastPasswordChange' => function() use ($faker) { return $faker->dateTimeThisYear(); },
        ]);
        $populator->execute();
    }
}
