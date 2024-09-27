<?php
    class User 
    {
        private $id;
        private $firstname;
        private $lastname;
        private $birthdate;
        private $email;
        private $password;
        private $isActive;

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
            $sql = "SELECT * FROM users WHERE email = :pEmail;";
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
            $sql = "UPDATE users SET isActive = :pIsActive WHERE usr_id = :pId;";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pIsActive", $isActive, PDO::PARAM_INT);
            $query->bindParam("pId", $id, PDO::PARAM_INT);
            return $query->execute(); // Retourne un boolean
        }

        public function insertUser($firstname, $lastname, $email, $password, $birthdate) {
            $sql = "INSERT INTO users (firstname, lastname, email, password, birthdate, isActive, type) 
                    VALUES (:pFirstname, :pLastname, :pEmail, :pPassword, :pBirthdate, 1, 'user');";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pFirstname", $firstname);
            $query->bindParam("pLastname", $lastname);
            $query->bindParam("pEmail", $email);
            $query->bindParam("pPassword", $password);
            $query->bindParam("pBirthdate", $birthdate);
        
            return $query->execute();
        }

    }
?>