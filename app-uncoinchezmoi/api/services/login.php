<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    include_once '../config/database.php';
    include_once '../model/user.php';

    // TODO Gestion de l'appel avec axios, 
    //$users = getUsers($pdo);

    // echo json_encode($users);
    // true -> connexion ok, false sinon
    // code de retour
    function login($email, $password) {
        $user = getUsersByEmail($email);

        if ($user == null) {
            return false;
        }

        // Algorithm BCRYPT derrière
        $hashedPassword = $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if (password_verify($password, $hashed_password)) {
            return true;
        } else {
            return false;
        } 

        // TODO Double authentification ?
        //return true;
    }
?>
