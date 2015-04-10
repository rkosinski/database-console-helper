<?php
namespace Kos\Services;

use Kos\Interfaces\DatabaseInterface;

class DatabaseCreationService
{
    private $connection;

    /**
     * @param DatabaseInterface $connection
     */
    public function __construct(DatabaseInterface $connection)
    {
        $this->connection = $connection->getDatabaseConnection();
        $this->configuration = $connection->getDatabaseConfiguration();
    }

    /**
     * Create database based on url name
     *
     * @param $url
     * @return array|int
     */
    public function createDatabase($url)
    {
        // First validate the url
        $this->validateUrlName($url);

        // Generate database nane
        $database = $this->generateDatabaseName($url);
        // Generate username
        $user = $this->generateUserName($url);
        // Generate password
        $password = $this->generateRandomPassword();

        // Try/catch to check if database or user already exists
        try {
            // Create database
            $this->connection->exec("CREATE DATABASE `$database`;");
            // Create user and grant privileges
            $this->connection->exec("CREATE USER '$user'@'localhost' IDENTIFIED BY '$password';");
            $this->connection->exec("GRANT ALL PRIVILEGES ON `$database`.* TO `$user`@'localhost' WITH GRANT OPTION;");
            // Flush privileges to refresh them in the database
            $this->connection->exec("FLUSH PRIVILEGES;");
        } catch (\PDOException $e) {
            throw new \PDOException('Database or user already exists');
        }

        return array(
            'username' => $user,
            'database' => $database,
            'password' => $password,
        );
    }

    /**
     * Validate url name
     *
     * @param $url
     * @return bool
     */
    private function validateUrlName($url)
    {
        // Check if url is not proper
        if (!preg_match('/^[a-zA-Z0-9\.-]+$/', $url)) {
            // And throw invalid argument exception
            throw new \InvalidArgumentException('Provided url name is not correct');
        }

        return true;
    }

    /**
     * Generate specified length username
     *
     * @param $url
     * @return string
     */
    private function generateUserName($url)
    {
        // Get suffix
        $suffix = $this->configuration['suffix'];
        // Max username length (based on mysql restrictions)
        $maxLength = 16;

        // If suffix exists change max length
        if ($suffix != null) {
            $maxLength -= strlen($suffix) + 1;
        }

        // Create username - replace special characters and change length
        $user = substr(trim(preg_replace('/(\.|\-)/', '_', $this->removeDomainFromUrl($url))), 0, $maxLength);

        // Return user name with appended suffix (if suffix exists)
        return $this->appendSuffixToField($user, $suffix);
    }

    /**
     * Generate database name
     *
     * @param $url
     * @return string
     */
    private function generateDatabaseName($url)
    {
        // Replace incorrect characters
        $databaseName = trim(preg_replace('/(\.|\-)/', '_', $this->removeDomainFromUrl($url)));

        // Return database name with appended suffix (if suffix exists)
        return $this->appendSuffixToField($databaseName, $this->configuration['suffix']);
    }

    /**
     * Append suffix to the field
     *
     * @param $field
     * @param $suffix
     * @return string
     */
    private function appendSuffixToField($field, $suffix)
    {
        // Add suffix if exists
        if ($suffix != null) {
            $field .= '_' . $suffix;
        }

        return $field;
    }

    /**
     * Generate secure random password
     *
     * @return string
     */
    private function generateRandomPassword()
    {
        $passwordLength = 10;
        $availableChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        return substr(str_shuffle($availableChars), 0, $passwordLength);
    }

    /**
     * Remove domain from the url
     *
     * @param $url
     * @return string
     */
    private function removeDomainFromUrl($url)
    {
        // Get url items exploded by dot
        $urlItems = explode('.', $url);
        // Remove latest element (in domain its *.pl or *.com)
        array_pop($urlItems);
        // Glue url items into one string
        return implode('_', $urlItems);
    }
}