<?php
    class Comment {
        private $id;
        private $text;
        private $createdAt;
        private $idAuthor;
        private $idPost;

        private $conn;

        public function __construct($database) {
            $this->conn = $database;
        }

        public function getAllComments() {
            $sql = "SELECT * FROM comment;";
            $query = $this->conn->prepare($sql);
            $query->execute();

            return $query->fetchAll();
        }

        public function getCommentsByPost($pIdPost) {
            $sql = "SELECT * FROM comment WHERE id_post = :pIdPost;";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pIdPost", $pIdPost);
            $query->execute();

            return $query->fetchAll();
        }

        public function insertNewComment($pText, $pCreatedAt, $pIdAuthor, $pIdPost) {
            $sql = "INSERT INTO comment (content, creation_date, id_user, id_post) 
                VALUES (:pComText, :pComCreatedAt, :pComIdAuthor, :pComIdPost);";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pComText", $pText);
            $query->bindParam("pComCreatedAt", $pCreatedAt);
            $query->bindParam("pComIdAuthor", $pIdAuthor);
            $query->bindParam("pComIdPost", $pIdPost);

            return $query->execute();
        }


    }
?>