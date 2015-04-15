<?php
namespace Kos\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Kos\Commands\MachineListCommand;
use Kos\DatabasePdoConnection;
use Kos\Services\DatabaseCreationService;
use Kos\ConfigYmlDataParser;

class DatabaseCreationCommand extends Command
{
    private $db;

    /**
     * @param $inputArguments
     */
    public function setDatabaseServiceConnection($inputArguments)
    {
        $config = new ConfigYmlDataParser();
        $connection = new DatabasePdoConnection($config, $inputArguments['host']);

        $this->db = new DatabaseCreationService($connection);
    }

    /**
     * Configure console command
     */
    protected function configure()
    {
        $this
            ->setName('database:create')
            ->addArgument('host',
                InputArgument::REQUIRED,
                'Required: Provide database host machine name from configuration file')
            ->addArgument('url',
                InputArgument::REQUIRED,
                'Required: Provide url name')
            ->setDescription('Create new database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputArguments = array(
            'host' => $input->getArgument('host'),
            'url' => $input->getArgument('url'),
        );

        $this->setDatabaseServiceConnection($inputArguments);

        $createdDatabase = $this->db->createDatabase($inputArguments['url']);

        $output->writeln("\n<info>Creating new database on <bg=blue;options=bold>" . $inputArguments['host'] . "</bg=blue;options=bold> machine</info>");

        $output->writeln("<info>Database: <bg=blue;options=bold>" . $createdDatabase['database'] . "</bg=blue;options=bold></info>");
        $output->writeln("<info>Username: <bg=blue;options=bold>" . $createdDatabase['username'] . "</bg=blue;options=bold></info>");
        $output->writeln("<info>Password: <bg=blue;options=bold>" . $createdDatabase['password'] . "</bg=blue;options=bold></info>");

        return 1;
    }

}