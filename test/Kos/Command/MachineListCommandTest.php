<?php

namespace Kos\Commands;

use Kos\Commands\MachineListCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class MachineListCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $command;
    protected $commandTester;

    /**
     * SetUp on test init
     */
    public function setUp()
    {
        $application = new Application();
        $application->add(new MachineListCommand());

        $this->command = $application->find('machines:list');
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * Test execute proper command
     */
    public function testExecuteCommand()
    {
        $this->commandTester->execute(
            array(
                'command' => $this->command->getName(),
            )
        );

        $this->assertRegExp('/List of development\/production machines/', $this->commandTester->getDisplay());
        $this->assertRegExp('/Machine name/', $this->commandTester->getDisplay());
        $this->assertRegExp('/Host address/', $this->commandTester->getDisplay());
    }
}