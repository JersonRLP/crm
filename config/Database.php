<?php

class Database {
    private $host = "192.168.1.8";
    private $db_name = "gat";
    private $username = "root";
    private $password = "Scmj010400*";
    public $conn;

    private $host_crm = "localhost";
    private $db_name_crm = "crm_manager";
    private $username_crm = "root";
    private $password_crm = "";
    public $conn_crm;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_PERSISTENT => true, // Habilita conexión persistente
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
        } catch (PDOException $exception) {
            die("Error de conexión: " . $exception->getMessage());
        }
        return $this->conn;
    }

        public function getConnectionCrm() {
        $this->conn_crm = null;
        try {
            $this->conn_crm = new PDO(
                "mysql:host=" . $this->host_crm . ";dbname=" . $this->db_name_crm . ";charset=utf8mb4",
                $this->username_crm,
                $this->password_crm,
                [
                    PDO::ATTR_PERSISTENT => true, // Habilita conexión persistente
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
        } catch (PDOException $exception) {
            die("Error de conexión: " . $exception->getMessage());
        }
        return $this->conn_crm;
    }
}

