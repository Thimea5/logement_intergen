<?php
    class Review {
        private $id;
        private $score;
        private $comment;
        private $creation_date;
        private $id_user;
        private $id_post;

        private $conn;

        public function __construct($database) {
            $this->conn = $database;
        }

        public function getAllReviews() {
            $sql = "SELECT r.* FROM review r;";

            $query = $this->conn->prepare($sql);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getReviewsByPost($pId) {
            $sql = "SELECT r.* FROM review r WHERE r.id_post = :pId;";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pId", $pId);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insertReview() {
            $sql = "INSERT INTO review (score, comment, creation_date, id_user, id_post) 
                VALUES (:pScore, :pComment, :pCreationDate, :pIdUser, :pIdPost);";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pScore", $this->score);
            $query->bindParam("pComment", $this->comment);
            $query->bindParam("pCreationDate", $this->creation_date);
            $query->bindParam("pIdUser", $this->id_user);
            $query->bindParam("pIdPost", $this->id_post);

            return $query->execute();
        }

        public function deleteReviewById($pId) {
            $sql = "DELETE r.* FROM review r WHERE r.id = :pId;";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pId", $pId);

            return $query->execute();
        }

        public function updateComment($pId) {
            $sql = "UPDATE review r SET r.comment = :pComment, r.creation_date = :pCreationDate WHERE r.id = :pId;";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pId", $pId);
            $query->bindParam("pComment", $this->content);
            $query->bindParam("pCreationDate", $this->creation_date);

            return $query->execute();
        }


    }
?>