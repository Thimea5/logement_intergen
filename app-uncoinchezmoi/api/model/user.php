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

            return $query->fetch(PDO::FETCH_ASSOC); 
        }

        function getUsersById($id) {
            $sql = "SELECT * FROM users WHERE id = :pId;";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pId", $id);
            $query->execute();

            return $query->fetch(PDO::FETCH_ASSOC); 
        }

        /*function desactivateUserById($id) {
            return ;
        }*/

    }
?>