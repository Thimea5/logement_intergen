<?php
    class Conversation {
        private $id;
        private $idUser1;
        private $idUser2;
        private $creationDate;

        private $conn;

        public function __construct($database) {
            $this->conn = $database;  
        }

        public function getAllConversation() {
            $sql = "SELECT * FROM `conversation`;";
            
            $query = $this->conn->prepare($sql);
            $query->execute();
            
            return $query->fetchAll();
        }

        public function getUserConversation($pUserId) {
            $sql = "SELECT * FROM conversation where id_user1 = :pId or id_user2 = :pId;";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pId", $pUserId);
            $query->execute();

            return $query->fetchAll();
        }

        public function insertConversation() {
            $sql = "INSERT INTO conversation (creation_date, id_user1, id_user2) 
                VALUES();";
            return 0;
        }

        public function updateDateConversation() {
            return 0;
        }


    }
?>