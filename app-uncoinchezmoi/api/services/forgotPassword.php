<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json');

    include_once '../config/database.php';
    include_once '../model/user.php';

    $data = json_decode(file_get_contents("php://input"));    
    
    if ($data === null) {
        echo json_encode(["success" => false, "message" => "Aucune donnée reçue."]);
        exit;
    }

    if (!isset($data->mail) || !isset($data->password)) {
        echo json_encode(["success" => false, "message" => "Paramètres manquants."]);
        exit;
    }

    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    $passwordHashed = password_hash($data->password, PASSWORD_DEFAULT);
    $result = $user->updatePassword($data->mail, $passwordHashed);

    if ($result) {
        echo json_encode(['success' => true,]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur']);
    }
?>
