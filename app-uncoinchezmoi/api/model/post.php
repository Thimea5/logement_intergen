<?php
  class Post {
    private $id;
    private $idHost;
    private $handicap;
    private $smoking;
    private $pets;
    private $description;
    private $price;
    private $isAvailable;

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

    // TODO : Revoir la séparation entre Host et Post !?
    public function getPostsWithHostInformations() {
      $sql = "SELECT post.*, host.*
              FROM post 
              JOIN host ON post.id_host = host.id;";

      $query = $this->conn->prepare($sql);
      $query->execute();

      return $query->fetchAll(PDO::FETCH_ASSOC);
    }    
  }
?>