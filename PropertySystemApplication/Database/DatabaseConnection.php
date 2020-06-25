<?php
    class Database {
        private $host = "localhost";
        private $dbName = "trial";
        private $dbUsername = "root";
        private $dbPassword = "";
        private $dbPort = "3307";
        private $conn;

        public function connect() {
            $this->conn = null;

            try {
                $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->dbName.";port=".$this->dbPort, $this->dbUsername, $this->dbPassword);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection Error: ".$e->getMessage();
            }

            return $this->conn;
        }
    }
?>