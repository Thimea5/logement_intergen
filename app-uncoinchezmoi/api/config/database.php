<?php
    class Database {
        private $host = 'localhost';
        private $db = 'db-uncoinchezmoi';
        private $user = 'root';  // MySQL, root sans mot de passe
        private $password = ''; 
        public $connection;

        public function connect() {
            $this->connection = null;
            try {
                $this->connection = new PDO("mysql:host=$host; dbname=$db", $user, $password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            return $this->connection;
        }
    }    
?>