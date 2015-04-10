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
    public function getConfigurationData()
    {
        // Check if config file not exist
        if (!file_exists(self::CONFIG_FILE_PATH)) {
            // And throw invalid argument exception
            throw new \InvalidArgumentException('Provided config file not exists');
        }

        // Parse yaml config file
        $config = Yaml::parse(file_get_contents(self::CONFIG_FILE_PATH));

        return $config;
    }

}