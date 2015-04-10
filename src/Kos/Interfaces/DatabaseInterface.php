<?php
namespace Kos\Interfaces;

use Kos\Interfaces\ConfigDataInterface;

interface DatabaseInterface
{
    public function __construct(ConfigDataInterface $config, $hostName);

    public function getDatabaseConnection();

    public function getDatabaseConfiguration();
}