<?php
    class Host 
    {
        private $id;
        private $city;
        private $postal_code;
        private $adress;
        private $lat;
        private $lng;
        private $photo;
        private $type_logement;
        private $handicap;
        private $smoking;
        private $pets;
        private $shopping;
        private $gardening;
        private $chores;
        private $price;
        private $size;

        private $conn;

        public function __construct($database)
        {
            $this->conn = $database;
        }

        public function getHosts() {
            $sql = "SELECT * FROM host;";
            $query = $this->conn->prepare($sql);
            $query->execute();

            return $query->fetchAll();
        }

        public function insertHost($id)
        {
            $photoPath = "host_photo".$id;
            $sql = "INSERT INTO host (id, city, postal_code, address, lat, lng, cheminPhoto, type_logement, handicap, smoking, pets, shopping, gardening, chores, price, size, nbPhoto)
                        VALUES (:pId, NULL, NULL, NULL, NULL, NULL, :pPhotoPath, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pId", $id);
            $query->bindParam("pPhotoPath", $photoPath);

            return $query->execute();
        }

        public function updateHost($id, $city, $postal_code, $adress, $lat, $lng, $photo, $type_logement, $handicap, $smoking, $pets, $shopping, $gardening, $chores, $price, $size)
        {
            $sql = "UPDATE host 
                        SET city = :pCity, postal_code = :pPostalCode, adress = :pAdress, lat = :pLat, lng = :pLng, photo = :pPhoto, 
                            type_logement = :pTypeLogement, handicap = :pHandicap, smoking = :pSmoking, pets = :pPets, 
                            shopping = :pShopping, gardening = :pGardening, chores = :pChores, price = :pPrice, size = :pSize
                        WHERE id = :pId;";

            $query = $this->conn->prepare($sql);
            
            $query->bindParam("pId", $id);
            $query->bindParam("pCity", $city);
            $query->bindParam("pPostalCode", $postal_code);
            $query->bindParam("pAdress", $adress);
            $query->bindParam("pLat", $lat);
            $query->bindParam("pLng", $lng);
            $query->bindParam("pPhoto", $photo);
            $query->bindParam("pTypeLogement", $type_logement);
            $query->bindParam("pHandicap", $handicap);
            $query->bindParam("pSmoking", $smoking);
            $query->bindParam("pPets", $pets);
            $query->bindParam("pShopping", $shopping);
            $query->bindParam("pGardening", $gardening);
            $query->bindParam("pChores", $chores);
            $query->bindParam("pPrice", $price);
            $query->bindParam("pSize", $size);

            return $query->execute();
        }

        public function getHostById($id)
        {
            $sql = "SELECT * FROM host WHERE id = :pId;";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pId", $id);
            $query->execute();

            return $query->fetch();
        }



    }
?>