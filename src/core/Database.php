<?php

class Database
{
    private $host = "db";
    private $db_name = "adsi";
    private $username = "mvc_user";
    private $password = "mvc_password";
    public $conn;


    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
