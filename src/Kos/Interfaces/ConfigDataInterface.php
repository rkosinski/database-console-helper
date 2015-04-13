<?php
namespace Kos\Interfaces;

interface ConfigDataInterface
{

    /**
     * @return array
     */
    public function getAllConfigurationData();

    /**
     * @param $hostName
     * @return array
     */
    public function getSingleConfigurationDataByHostName($hostName = 'default');

}