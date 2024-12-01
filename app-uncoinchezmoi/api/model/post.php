<?php
  class Post {
    private $conn;

    public function __construct($database) {
      $this->conn = $database;  
    }

    public function getPosts() {
      $sql = "SELECT * FROM post;";

      $query = $this->conn->prepare($sql);
      $query->execute();

      return $query->fetchAll();
    }

    public function getPostsWithHostInformations() {
      $sql = "SELECT post.*, users.*
              FROM post 
              JOIN users ON post.id_user = users.id;";

      $query = $this->conn->prepare($sql);
      $query->execute();
      
      return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertPost($city, $postal_code, $address, $lat, $lng, $cheminPhoto, $type_logement, $description, $price, $size, $nb_photo, $available, $id_user) {
      $sql = "INSERT INTO post (city, postal_code, `address`, lat, lng, cheminPhoto, type_logement, `description`, price, size, nb_photo, available, id_user) 
              VALUES (:pCity, :pPostal_code, :pAddress, :pLat, :pLng, :pCheminPhoto, :pType_logement, :pDescription, :pPrice, :pSize, :pNb_photo, :pAvailable, :pId_user)";
  
      $query = $this->conn->prepare($sql);
      
      $query->bindParam("pCity", $city);
      $query->bindParam("pPostal_code", $postal_code);
      $query->bindParam("pAddress", $address);
      $query->bindParam("pLat", $lat);
      $query->bindParam("pLng", $lng);
      $query->bindParam("pCheminPhoto", $cheminPhoto);
      $query->bindParam("pType_logement", $type_logement);
      $query->bindParam("pDescription", $description);
      $query->bindParam("pPrice", $price);
      $query->bindParam("pSize", $size);
      $query->bindParam("pNb_photo", $nb_photo);
      $query->bindParam("pAvailable", $available);
      $query->bindParam("pId_user", $id_user);
  
      return $query->execute();
    }
  
  }
?>