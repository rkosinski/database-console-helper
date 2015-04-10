<?php
namespace Kos;

use Kos\Interfaces\ConfigDataInterface;
use Kos\Interfaces\DatabaseInterface;

class DatabasePdoConnection implements DatabaseInterface
{
    private $dbh;
    private $hostConfig = array();

    /**
     * @param ConfigDataInterface $configData
     * @param $hostName
     */
    public function __construct(ConfigDataInterface $configData, $hostName)
    {
        // Check for database configuration file
        if (!$this->getDatabaseConfigurationByConfigHostName($configData, $hostName)) {
            throw new \InvalidArgumentException('Provided database host name not found.');
        }

        // Connect to the database host based on host config file
        try {
            $this->dbh = new \PDO("mysql:host=", $this->hostConfig[0]['root'], $this->hostConfig[0]['password']);
            // Set PDO error handler
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \PDOException('Database error. Incorrect credentials.');
        }
    }

    /**
     * Get config data by host name
     *
     * @param $configData
     * @param $hostName
     * @return bool
     */
    public function getDatabaseConfigurationByConfigHostName($configData, $hostName)
    {
        $data = $configData->getConfigurationData();

        // Parse config file to array
        foreach ($data as $key => $value) {
            // And get the host name configuration
            if ($key === $hostName) {
                array_push($this->hostConfig, $value);
            }
        }

        // Return if size of array is bigger than zero
        return sizeof($this->hostConfig) > 0;
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
        return $this->hostConfig[0];
    }

}