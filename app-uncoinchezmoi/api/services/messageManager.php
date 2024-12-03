<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    include_once '../config/database.php';
    include_once '../model/user.php';
    include_once '../model/conversation.php';
    include_once '../model/message.php';

    $database = new Database();
    $db = $database->connect();

    $userModel = new User($db);
    $convModel = new Conversation($db);
    $msgModel = new Message($db);

    $requestInfo = $_SERVER['REQUEST_METHOD'];

    if ($requestInfo === "POST") {
        $inputData = json_decode(file_get_contents("php://input"), true);  // Pour récupérer les données envoyées en POST

        if (!isset($inputData['content']) || !isset($inputData['conversation_id']) || !isset($inputData['user_id'])) {
            echo json_encode(["success" => false, "message" => "Données manquantes."]);
            exit;
        }

        $content = $inputData['content'];
        $conversationId = $inputData['conversation_id'];
        $userId = $inputData['user_id'];
        $creationDate = date('Y-m-d H:i:s');  // Date et heure actuelles

        $success = $msgModel->insertNewMessage($content, $creationDate, $conversationId, $userId);

        if ($success) {
            echo json_encode(["success" => true, "message" => "Message envoyé avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'envoi du message."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Méthode non autorisée."]);
    }
?>
