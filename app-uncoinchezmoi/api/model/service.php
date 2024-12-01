<?php
    class Service {
        private $id;
        private $idHost;
        private $isGardening;
        private $isErrand;
        private $isDiy;
        private $isCleanning;
        private $isTalking;
        private $isCooking;
        private $isPetsSitting;
        private $isCarSharing;
        private $isRepairs;

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

        public function getServicesByHost($pIdPost) {
            $sql = "SELECT * FROM services WHERE serv_idHost = :pIdHost;";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pIdHost", $pIdPost);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

    }
?>