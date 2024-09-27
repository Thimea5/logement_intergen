<?php
    class Database {
        private $host = 'localhost';
        private $db = 'db-uncoinchezmoi';
        private $user = 'root';
        private $password = '';
        public $connection;

        public function connect() {
            $this->connection = null;
            try {
                $this->connection = new PDO("mysql:host=$this->host; dbname=$this->db", $this->user, $this->password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $this->connection;
        }
    }    
?>