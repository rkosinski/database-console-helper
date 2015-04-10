<?php
namespace Kos;

use Kos\Interfaces\ConfigDataInterface;
use Kos\Interfaces\DatabaseInterface;

class DatabasePdoConnection implements DatabaseInterface
{
    private $dbh;
    private $config = array();

    /**
     * @param ConfigDataInterface $config
     * @param $hostName
     */
    public function __construct(ConfigDataInterface $config, $hostName)
    {
        $this->config = $config->getConfigurationDataByHostName($hostName)[0];
        // Check for database configuration file
        if (!sizeof($this->config) > 0) {
            throw new \InvalidArgumentException('Provided database host name not found.');
        }

        // Connect to the database host based on host config file
        try {
            $this->dbh = new \PDO("mysql:host=", $this->config['root'], $this->config['password']);
            // Set PDO error handler
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \PDOException('Database error. Incorrect credentials.');
        }
    }

    /**
     * @return \PDO
     */
    public function getDatabaseConnection()
    {
        return $this->dbh;
    }

    /**
     * @return array
     */
    public function getDatabaseConfiguration()
    {
        return $this->config;
    }

}