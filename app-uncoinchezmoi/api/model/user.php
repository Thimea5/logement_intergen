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
        private $tableName = 't_users_usr';

        public function __construct($database) {
            $this->conn = $database;
        }

        public function getUsers() {
            $sql = "SELECT * FROM ". $this->tableName ." ;";
            $query = $this->conn->prepare($sql);
            return $query->execute();
        }

        function getUsersByEmail($email) {
            $sql = "SELECT * FROM t_users_usr WHERE email = :pEmail;";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pEmail", $email);
            return $query->execute();
        }

        function getUsersById($id) {
            $sql = "SELECT * FROM t_users_usr WHERE id = :pId ;";
            $query = $this->conn->prepare($sql);
            $query->bindParam("pId", $id);
            return $query->execute();
        }

        function desactivateUserById($id) {
            return ;
        }

        // TODO insert, etc.
    }
?>