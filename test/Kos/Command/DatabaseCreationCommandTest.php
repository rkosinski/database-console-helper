<?php

namespace Kos\Commands;

use Kos\Commands\DatabaseCreationCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DatabaseCreationCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $command;
    protected $commandTester;

    /**
     * SetUp on test init
     */
    public function setUp()
    {
        $application = new Application();
        $application->add(new DatabaseCreationCommand());

        $this->command = $application->find('database:create');
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * @expectedException \PDOException
     * @expectedExceptionMessage Database error. Incorrect credentials.
     */
    public function testingIncorrectHostingCredentials()
    {
        $this->commandTester->execute(
            array(
                'command' => $this->command->getName(),
                'host' => 'production',
                'url' => 'rkosinski.dev.pl'
            )
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Provided url name is not correct
     */
    public function testingIncorrectUrlCredentials()
    {
        $this->commandTester->execute(
            array(
                'command' => $this->command->getName(),
                'host' => 'development',
                'url' => 'wrong@123&*!'
            )
        );
    }

}