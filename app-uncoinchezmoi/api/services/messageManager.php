<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
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
        $inputData = json_decode(file_get_contents("php://input"), true);  

        if (!isset($inputData['content']) || !isset($inputData['conv_id']) || !isset($inputData['user_id'])) {
            echo json_encode(["success" => false, "message" => "Données manquantes."]);
            exit;
        }

        $content = $inputData['content'];
        $conversationId = $inputData['conv_id'];
        $userId = $inputData['user_id'];
        date_default_timezone_set('Europe/Paris');
        $creationDate = date('Y-m-d H:i:s');  // Date et heure actuelles

        $success = $msgModel->insertNewMessage($content, $creationDate, $conversationId, $userId);
        //var_dump($success);
        if ($success) {
            echo json_encode(["success" => true, "message" => "Message envoyé avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'envoi du message."]);
        }
    } else if ($requestInfo === "DELETE") {
        $data = json_decode(file_get_contents("php://input"), true);
         if ($msgModel->deleteMessageById($data['id'])) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
    }else {
        $inputData = $_GET;

        if (!isset($inputData)) {
            echo json_encode(["success" => false, "message" => "Aucune donnée reçue."]);
            exit;
        }

        if (!isset($inputData['id'])) { 
            echo json_encode(["success" => false, "message" => "Paramètres manquants."]);
            exit;
        }

        //var_dump($inputData);

        $listMsg = $msgModel->getConversationMessage($inputData['id']);
        //var_dump($listMsg);
        echo json_encode($listMsg);
    } 
?>
