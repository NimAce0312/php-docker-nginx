<?php

class Database
{
    private $host = 'db';
    private $user = 'root';
    private $pass = 'root';
    private $dbname = 'docker-apache';

    private $dbh;
    private $stmt;
    private $error;

    // Establish connection to database
    public function __construct()
    {
        try {
            // Connect to MySQL without specifying the database
            $conn = new PDO("mysql:host=$this->host", $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create the database if it doesn't exist
            $conn->exec("CREATE DATABASE IF NOT EXISTS `$this->dbname`");

            // Reconnect to the MySQL server with the newly created database
            $conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Assign the connection to the class property
            $this->dbh = $conn;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo "Connection Failed: " . $this->error;
        }
    }

    // Prepare statement with query
    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    // Bind values
    public function bindValues($values = [])
    {
        foreach ($values as $index => $value) {
            $this->stmt->bindValue($index + 1, $value);
        }
    }

    // Execute the prepared statement
    public function execute()
    {
        return $this->stmt->execute();
    }

    // Get result set as object
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get a single record as object
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    // Get last inserted ID
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    // Function to create tables if they do not exist
    public function createTablesIfNotExist()
    {
        $tables = [
            'tbl_users' => "
                CREATE TABLE IF NOT EXISTS `tbl_users` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255) NOT NULL,
                    `email` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB;
            ",
        ];

        foreach ($tables as $table => $createQuery) {
            try {
                // Execute the create table query
                $this->dbh->exec($createQuery);
            } catch (PDOException $e) {
                echo "Error creating table '$table': " . $e->getMessage() . "<br>";
            }
        }
    }
}
