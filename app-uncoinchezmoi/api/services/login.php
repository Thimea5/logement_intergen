<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../model/user.php';

    $data = json_decode(file_get_contents("php://input"));
    // var_dump($data);

    if ($data === null) {
        echo json_encode(["success" => false, "message" => "Aucune donnée reçue."]);
        exit;
    }

    if (!isset($data->email) || !isset($data->password)) {
        echo json_encode(["success" => false, "message" => "Paramètres manquants."]);
        exit;
    }

    // Connexion à la base de données
    $database = new Database();
    $db = $database->connect();

    $user = new User($db);

    // Recherche de l'utilsateur en base 
    $userData = $user->getUsersByEmail($data->email);
    //var_dump($userData);

    if ($userData === false) {
        echo json_encode(["success" => false, "message" => "Utilisateur non trouvé."]);
        exit;
    } else {
        // étape 2 - Vérification du mot de passe

        if (password_verify($data->password, $userData['password'])) {
            echo json_encode(["success" => true, "message" => "Connexion réussie."]);
        } else {
            echo json_encode(["success" => false, "message" => "Mot de passe incorrect."]);
        }
    }
?>
