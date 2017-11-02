<?php

namespace A3020\FakeData\Provider;

use A3020\FakeData\Console\Command\CreateLogsCommand;
use A3020\FakeData\Console\Command\CreateUsersCommand;
use Concrete\Core\Application\Application;

class FakeDataServiceProvider
{
    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        if ($this->app->isRunThroughCommandLineInterface()) {
            $this->registerConsoleCommands();
        }
    }

    private function registerConsoleCommands()
    {
        $console = $this->app->make('console');
        $console->add(new CreateLogsCommand());
        $console->add(new CreateUsersCommand());
    }
}
