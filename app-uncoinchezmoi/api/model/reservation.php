<?php
    class Reservation {
        private $conn;

        public function __construct($database) {
            $this->conn = $database;  
        }

        public function insertNewReservation($idPost, $startDate, $endDate, $idUser, $cost) {
            $sql = "INSERT INTO reservation (id_post, start_date, end_date, id_user, cost)
                      VALUES (:id_post, :pStart_date, :end_date, :id_user, :cost)";
            
            $query = $this->conn->prepare($sql);
            $query->bindParam(':id_post', $idPost, PDO::PARAM_INT);
            $query->bindParam(':pStart_date', $startDate);
            $query->bindParam(':end_date', $endDate);
            $query->bindParam(':id_user', $idUser, PDO::PARAM_INT);
            $query->bindParam(':cost', $cost, PDO::PARAM_STR);

            return $query->execute();
        }   

        public function getAllReservations() {
            $sql = "SELECT * FROM reservation;";

            $query = $this->conn->prepare($sql);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getUserReservations($pId) {
            $sql = "SELECT r.* FROM reservation r WHERE r.id_user = :pId;";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pId", $pId);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>