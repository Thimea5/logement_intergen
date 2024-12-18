<?php 
    class Message {
        private $conn;

        public function __construct($database) {
            $this->conn = $database;  
        }

        public function getAllMessages() {
            $sql = "SELECT * FROM message;";

            $query = $this->conn->prepare($sql);
            $query->execute();

            return $query->fetchAll();
        }

        public function getConversationMessage($pConvId) {
            $sql = "SELECT * FROM message WHERE id_conversation = :pConvId;";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pConvId", $pConvId);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insertNewMessage($content, $creationDate, $convId, $userId) {
            $sql = "INSERT INTO message(content, creation_date, id_conversation, id_user)
                VALUES (:pContent, :pCreationDate, :pConvId, :pUserId);";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pContent", $content);
            $query->bindParam("pCreationDate", $creationDate);
            $query->bindParam("pConvId", $convId);
            $query->bindParam("pUserId", $userId);
            
            return $query->execute();
        }

        public function deleteMessageById($pId) {
            $sql = "DELETE m.* FROM `message` m WHERE m.id = :pId;";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pId", $pId);

            return $query->execute();
        }
    }
?>