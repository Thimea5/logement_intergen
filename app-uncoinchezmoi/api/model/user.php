<?php
    class User 
    {
        private $id;
        private $email;
        private $password;
        private $firstname;
        private $lastname;
        private $birthdate;
        private $isActive;
        private $genre;
        private $tel;
        private $maritalStatus;

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

        public function insertUser($email, $password, $firstname, $lastname, $birthdate, $genre, $tel, $maritalStatus) {
            $sql = "INSERT INTO users (mail, password, firstname, lastname, birthdate, genre, tel, marital_status, active, role) 
                    VALUES (:pEmail, :pPassword, :pFirstname, :pLastname, :pBirthdate, :pGenre, :pTel, :pMaritalStatus, 1, 'user');";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pEmail", $email);
            $query->bindParam("pPassword", $password);
            $query->bindParam("pFirstname", $firstname);
            $query->bindParam("pLastname", $lastname);
            $query->bindParam("pBirthdate", $birthdate);
            $query->bindParam("pGenre", $genre);
            $query->bindParam("pTel", $tel);
            $query->bindParam("pMaritalStatus", $maritalStatus);
        
            return $query->execute();
        }

    }
?>