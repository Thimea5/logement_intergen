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

        if (!isset($inputData)) {
            echo json_encode(["success" => false, "message" => "Aucune donnée reçue."]);
            exit;
        }

        if (!isset($inputData['id'])) { 
            echo json_encode(["success" => false, "message" => "Paramètres manquants."]);
            exit;
        }

        $userConvs = $convModel->getUserConversation($inputData['id']);    
        $s = [];
        $msgs = [];
        //var_dump($userConvs);   
        for ($i=0; $i <count($userConvs); $i++) {
            // Chargement des infos des utilisateurs destinataires (nom, img...)
            if ($userConvs[$i]['id_user1'] == $inputData['id']) {
                $usersTargetInfo = $userModel->getUsersById($userConvs[$i]['id_user2']);
            } else {
                $usersTargetInfo = $userModel->getUsersById($userConvs[$i]['id_user1']);
            }
            array_push($s, $usersTargetInfo);
            //var_dump($usersTargetInfo);
            //var_dump($s)
            
            // Chargement des messages
            array_push($msgs, $msgModel->getConversationMessage($userConvs[$i]['id']));
            
        }
        echo json_encode(["success"=>true, "conversations"=>$userConvs, "messages"=>$msgs, "users"=>$s]);
    } else {
        $inputData = json_decode(file_get_contents("php://input"), true);  
        if (!isset($inputData['idUser']) || !isset($inputData['idDest'])) {
            echo json_encode(["success" => false, "message" => "Données manquantes."]);
            exit;
        }

        //var_dump($inputData);
        $userId = $inputData['idUser'];
        $targetId = $inputData['idDest'];
        date_default_timezone_set('Europe/Paris');
        $creationDate = date('Y-m-d H:i:s');  

        //var_dump($userId, $targetId);
        $exists = $convModel->getAllConversation();

        //var_dump($exists);
        $bCreation = true;
        for ($i=0; $i<count($exists); $i++) {
            if ($exists[$i]['id_user1'] == $userId && $exists[$i]['id_user2']==$targetId) {
                $bCreation = false;
            }
        }
        //var_dump($bCreation);
        if ($bCreation) {
            $success = $convModel->insertConversation($creationDate, $userId, $targetId);
        } else {
            $success = 1;
        }
        
        //var_dump($success);
        if ($success) {
            echo json_encode(["success" => true, "message" => "Conversation crée avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'envoi du message."]);
        }
    }
?>