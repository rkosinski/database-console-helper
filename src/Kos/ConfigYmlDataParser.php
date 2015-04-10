<?php
namespace Kos;

use Symfony\Component\Yaml\Yaml;
use Kos\Interfaces\ConfigDataInterface;

class ConfigYmlDataParser implements ConfigDataInterface
{
    const CONFIG_FILE_PATH = 'app/config.yml';

    /**
     * Get configuration file
     * From the YML config
     *
     * @return array
     */
    public function getConfigurationDataByHostName($hostName)
    {
        // Init variables
        $hostConfig = array();

        // Check if config file not exist
        if (!file_exists(self::CONFIG_FILE_PATH)) {
            // And throw invalid argument exception
            throw new \InvalidArgumentException('Provided config file not exists');
        }

        // Parse yaml config file
        $config = Yaml::parse(file_get_contents(self::CONFIG_FILE_PATH));

        // Parse config file to array
        foreach ($config as $key => $value) {
            // And get the host name configuration
            if ($key === $hostName) {
                array_push($hostConfig, $value);
            }
        }

        return $hostConfig;
    }

}