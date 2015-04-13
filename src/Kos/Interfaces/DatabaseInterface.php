<?php
namespace Kos\Interfaces;

interface DatabaseInterface
{

    /**
     * @param ConfigDataInterface $config
     * @param string $hostName
     */
    public function __construct(ConfigDataInterface $config, $hostName = 'default');

    /**
     * @return \PDO
     */
    public function getDatabaseConnection();

    /**
     * @return array
     */
    public function getDatabaseConfiguration();

}