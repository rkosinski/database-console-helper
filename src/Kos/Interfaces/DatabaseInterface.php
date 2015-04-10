<?php
namespace Kos\Interfaces;

use Kos\Interfaces\ConfigDataInterface;

interface DatabaseInterface
{
    public function __construct(ConfigDataInterface $configData, $hostName);

    public function getDatabaseConfigurationByConfigHostName($configData, $hostName);

    public function getDatabaseConnection();

    public function getDatabaseConfiguration();
}