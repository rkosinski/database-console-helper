<?php

namespace Kos\Commands;

use Kos\Commands\DatabaseCreationCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DatabaseCreationCommandTest extends \PHPUnit_Framework_TestCase
{
    // TODO Tests, tests, tests

    protected $command;
    protected $commandTester;

    protected function setUp()
    {
        $application = new Application();
        $application->add(new DatabaseCreationCommand());

        $this->command = $application->find('database:create');
        $this->commandTester = new CommandTester($this->command);
    }

    public function testExecute()
    {
        $this->assertTrue(true);
    }
}