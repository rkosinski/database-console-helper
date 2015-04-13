<?php
namespace Kos;

use Symfony\Component\Yaml\Yaml;
use Kos\Interfaces\ConfigDataInterface;

class ConfigYmlDataParser implements ConfigDataInterface
{
    const CONFIG_FILE_PATH = 'app/config.yml';

    /**
     * @return array
     */
    public function getAllConfigurationData()
    {
        // Check if config file not exist
        if (!file_exists(self::CONFIG_FILE_PATH)) {
            // And throw invalid argument exception
            throw new \InvalidArgumentException('Provided config file not exists');
        }

        // Parse yaml config file
        return Yaml::parse(file_get_contents(self::CONFIG_FILE_PATH));
    }

    /**
     * @param string $hostName
     * @return array
     */
    public function getSingleConfigurationDataByHostName($hostName = 'default')
    {
        $hostConfig = array();

        // Get all configuration data
        $configData = $this->getAllConfigurationData();

        // Parse config data to array
        foreach ($configData as $key => $value) {
            // And get the host name configuration
            if ($key === $hostName) {
                array_push($hostConfig, $value);
            }
        }

        return $hostConfig;
    }

}