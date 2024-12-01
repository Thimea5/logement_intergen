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

    if ($requestInfo === "GET") {
        $inputData = $_GET;

        var_dump($inputData);

        if (!isset($inputData)) {
            echo json_encode(["success" => false, "message" => "Aucune donnée reçue."]);
            exit;
        }

        if (!isset($inputData['id'])) { 
            echo json_encode(["success" => false, "message" => "Paramètres manquants."]);
            exit;
        }

        $userConvs = $convModel->getUserConversation($inputData['id']);
        var_dump($userConvs);
        
        for ($i=1; $i <=count($userConvs); $i++) {
            // Chargement des infos des utilisateurs destinataires (nom, img...)
            if ($userConvs[$i]['id_user1'] == $inputData['id']) {
                $usersTargetInfo = $userModel->getUsersById($userConvs[$i]['id_user2']);
            } else {
                $usersTargetInfo = $userModel->getUsersById($userConvs[$i]['id_user1']);
            }
            var_dump($usersTargetInfo);
            
            // Chargement des messages
            $msgs = $msgModel->getConversationMessage($userConvs[$i]['id']);
            var_dump($msgs);
            
        }
        
    }
?>