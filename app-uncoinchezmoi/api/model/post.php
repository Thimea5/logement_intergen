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

    public function getPostByHostId($idUser) {
      $sql = "SELECT * FROM post WHERE id_user = :pIdUser";
      $query = $this->conn->prepare($sql);

      $query->bindParam("pIdUser", $idUser);

      $query->execute();
      
      return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostsWithHostInformations() {
      $sql = "SELECT 
                  post.id AS post_id, 
                  post.city, 
                  post.postal_code, 
                  post.address, 
                  post.lat, 
                  post.lng, 
                  post.cheminPhoto, 
                  post.type_logement, 
                  post.description, 
                  post.price, 
                  post.size, 
                  post.nb_photo, 
                  post.available, 
                  post.id_user, 
                  users.id AS user_id, 
                  users.mail, 
                  users.firstname, 
                  users.lastname, 
                  users.birthdate, 
                  users.genre, 
                  users.tel, 
                  users.marital_status, 
                  users.photo, 
                  users.active, 
                  users.type, 
                  users.isComplete
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

  public function updatePost($city, $postal_code, $address, $lat, $lng, $type_logement, $description, $price, $size, $id_post)
  {
    // Requête UPDATE pour modifier les informations du post
    $sql = "UPDATE post 
            SET city = :pCity, postal_code = :pPostal_code, address = :pAddress, lat = :pLat,
                lng = :pLng, type_logement = :pType_logement, description = :pDescription,
                price = :pPrice, size = :pSize
            WHERE id = :pId_post";  // Condition WHERE pour spécifier quel post mettre à jour

    $query = $this->conn->prepare($sql);

    // Lier les paramètres
    $query->bindParam("pCity", $city);
    $query->bindParam("pPostal_code", $postal_code);
    $query->bindParam("pAddress", $address);
    $query->bindParam("pLat", $lat);
    $query->bindParam("pLng", $lng);
    $query->bindParam("pType_logement", $type_logement);
    $query->bindParam("pDescription", $description);
    $query->bindParam("pPrice", $price);
    $query->bindParam("pSize", $size);

    $query->bindParam("pId_post", $id_post);  // Lier l'ID du post à mettre à jour

    return $query->execute();  // Exécuter la requête
  }

  
  }
?>