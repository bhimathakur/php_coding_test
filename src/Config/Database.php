<?php

namespace App\Config;

use PDO;
use PDOException;

/**
 * This class make the connection with database
 */
class Database implements DBInterface
{
    public PDO $conn;
    private array $config = array(
        'username' => 'root',
        'password' => '',
        'hostname' => 'localhost',
        'database' => 'php_coding_test'
    );

    public function __construct()
    {
        $this->connect();
    }

    /**
     * This function make the connection with db
     *
     * @return PDO
     */
    public function connect(): PDO
    {
        try {
            $db = $this->config;
            $servername = $db['hostname'];
            $username = $db['username'];
            $password = $db['password'];
            $database = $db['database'];
            $this->conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            return $this->conn;
        } catch (PDOException $e) {
            throw new \ErrorException($e);
        }
    }

    /**
     * This function return the db connection
     *
     * @return void
     */
    public function getDb()
    {
        if ($this->conn instanceof PDO) {
            return $this->conn;
        }
    }

    /**
     * This is generalized function fetch the data from database
     *
     * @param string $query
     * @param array|null $parameters
     * @param string $fetchRecord
     * @return array
     */
    public function query(string $query, ?array $parameters = null, string $fetchRecord = 'all'): array
    {
        $db  = $this->connect();
        $fetch = $fetchRecord === 'all' ? 'fetchAll' : 'fetch';
        $stmt = $db->prepare($query);
        $stmt->execute($parameters);
        return $stmt->$fetch(PDO::FETCH_ASSOC);
    }

    /**
     * This funciton return the last inserted id
     *
     * @return integer
     */
    public function lastInsertId(): int
    {
        return $this->conn->lastInsertId();
    }
}