<?php
    class User 
    {
        private $conn;

        public function __construct($database) {
            $this->conn = $database;
        }

        public function getUsers() {
            $sql = "SELECT * FROM users;";
            $query = $this->conn->prepare($sql);
            $query->execute();

            return $query->fetchAll();
        }

        function getUsersByEmail($email) {
            $sql = "SELECT * FROM users WHERE mail = :pEmail;";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pEmail", $email);
            $query->execute();

            return $query->fetch(PDO::FETCH_ASSOC); // Retourne les données 
        }

        function getUsersById($id) {
            $sql = "SELECT * FROM users WHERE id = :pId;";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pId", $id);
            $query->execute();

            return $query->fetch(PDO::FETCH_ASSOC); 
        }

        public function activateUserById($id, $isActive) {
            $sql = "UPDATE users SET active = :pActive WHERE usr_id = :pId;";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pActive", $isActive, PDO::PARAM_INT);
            $query->bindParam("pId", $id, PDO::PARAM_INT);
            return $query->execute(); // Retourne un boolean
        }

        public function insertUser($email, $password, $firstname, $lastname, $birthdate, $tel, $gender, $maritalStatus, $photo, $type, $complete) {
            $sql = "INSERT INTO users (mail, `password`, firstname, lastname, birthdate, genre, tel, marital_status, photo, active, `type`, isComplete)
                    VALUES (:pEmail, :pPassword, :pFirstname, :pLastname, :pBirthdate, :pGenre, :pTel, :pMaritalStatus, :pPhoto, 1, :pType, :pComplete);";

            $query = $this->conn->prepare($sql);

            $query->bindParam("pEmail", $email);
            $query->bindParam("pPassword", $password);
            $query->bindParam("pFirstname", $firstname);
            $query->bindParam("pLastname", $lastname);
            $query->bindParam("pBirthdate", $birthdate);
            $query->bindParam("pGenre", $gender);
            $query->bindParam("pTel", $tel);
            $query->bindParam("pMaritalStatus", $maritalStatus);
            $query->bindParam("pPhoto", $photo);
            $query->bindParam("pType", $type);
            $query->bindParam("pComplete", $complete);

            return $query->execute();
        }

        public function updatePassword($email, $password) {
            $sql = "UPDATE users SET `password` = :pPassword WHERE mail = :pMail;";

            $query = $this->conn->prepare($sql);
            $query->bindParam("pMail", $email);
            $query->bindParam("pPassword", $password);

            return $query->execute();
        }

    }
?>