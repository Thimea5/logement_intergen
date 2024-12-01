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
            $sql = "SELECT * FROM comment WHERE com_idPost = :pIdPost;";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pIdPost", $pIdPost);
            $query->execute();

            return $query->fetchAll();
        }

        public function insertNewComment($pText, $pCreatedAt, $pIdAuthor, $pIdPost) {
            $sql = "INSERT INTO comment (com_text, com_createdAt, com_idAuthor, com_idPost) 
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