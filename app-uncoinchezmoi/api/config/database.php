<?php
    class Database {
        private $host = 'mysql-uccm-user.alwaysdata.net';
        private $db = 'uccm-user_db-uncoinchezmoi';
        private $user = 'uccm-user';
        private $password = '@Citron&69';
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