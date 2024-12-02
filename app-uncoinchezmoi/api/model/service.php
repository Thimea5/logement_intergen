<?php
    class Service {
        private $conn;

        public function __construct($database) {
            $this->conn = $database;
        }

        public function getAllServices() {
            $sql = "SELECT * FROM services;";
            
            $query = $this->conn->prepare($sql);
            
            $query->execute();

            return $query->fetchAll();
        }

        public function getServicesByHost($pIdUser) {
            $sql = "SELECT * FROM services WHERE id_user = :pIdUser;";

            $query = $this->conn->prepare($sql);

            $query->bindParam("pIdUser", $pIdUser);

            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getServicesByPost($pPost) {
            $sql = "SELECT * FROM services WHERE id = :pId;";

            $query = $this->conn->prepare($sql);

            $query->bindParam("pId", $pPost);

            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insertServices($gardening, $errand, $diy, $cleaning, $chatting, $cooking, $petSitting, $carSharing, $pIdUser) {
            $sql = "INSERT INTO services (gardening, errand, diy, cleaning, chatting, cooking, petSitting, carSharing, id_user) VALUES
                                        (:pGardening, :pErrand, :pDiy, :pCleaning, :pChatting, :pCooking, :pPetSitting, :pCarSharing, :pId_user)";
            
            $query = $this->conn->prepare($sql);
            
            $query->bindParam("pGardening", $gardening);
            $query->bindParam("pErrand", $errand);
            $query->bindParam("pDiy", $diy);
            $query->bindParam("pCleaning", $cleaning);
            $query->bindParam("pChatting", $chatting);
            $query->bindParam("pCooking", $cooking);
            $query->bindParam("pPetSitting", $petSitting);
            $query->bindParam("pCarSharing", $carSharing);
            $query->bindParam("pId_user", $pIdUser);

            return $query->execute();
        }

    }
?>