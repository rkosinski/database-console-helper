<?php
namespace Kos\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Kos\ConfigYmlDataParser;
use Symfony\Component\Console\Helper\Table;

class MachineListCommand extends Command
{

    /**
     * Configure console command
     */
    protected function configure()
    {
        $this
            ->setName('machines:list')
            ->setDescription('List of development/production machines');
    }

    /**
     * @return array
     */
    public function getMachinesListData()
    {
        // Init variables
        $rowsData = array();

        // Get yml config data
        $config = new ConfigYmlDataParser();
        $data = $config->getConfigurationData();

        // Push data into rows data array
        foreach ($data as $key => $value) {
            array_push($rowsData, array($key, $value['host']));
        }

        return $rowsData;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>List of development/production machines</info>");

        // Init table and set headers
        $table = new Table($output);
        $table->setHeaders(array('Machine name', 'Host address'));

        // Add rows and render table
        $table->addRows($this->getMachinesListData());
        $table->render();

        return 1;
    }
}