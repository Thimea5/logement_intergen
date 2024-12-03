<?php
    class Reservation {
        private $conn;

        public function __construct($database) {
            $this->conn = $database;  
        }

        public function insertReservation($idPost, $idUser) {
            $sql = "INSERT INTO reservation (id_post, id_user) 
                VALUES (:pIdPost, :pIdUser);";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pIdPost", $idPost);
            $query->bindParam("pIdUser", $idUser);

            return $query->execute();
        }
    }
?>