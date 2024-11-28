<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    include_once '../config/database.php';
    include_once '../model/comment.php';
    include_once '../model/user.php';

    $database = new Database();
    $db = $database->connect();

    $commentModel = new Comment($db);
    $userModel = new User($db);

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

        $outputComments = $commentModel->getCommentsByPost($inputData['id']);
    
        if ($outputComments) {
            foreach ($outputComments as $keys => $values) {
                foreach($values as $key => $value) {
                    if ($key === 'com_idAuthor') {
                        $userInfo = $userModel->getUsersById($value);
                        $nameAuthor = $userInfo["firstname"] . ' ' . $userInfo['lastname'];

                        $outputComments[$keys]["nameAuthor"] = $nameAuthor;
                    }
                }
            }
            echo json_encode(['success' => true, 'comments' => $outputComments]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Aucun commentaire trouvé.']);
        }
    } else {
        $data = json_decode(file_get_contents("php://input"));   
        
        if ($data === null) {
            echo json_encode(['error' => 'Données invalides']);
            exit;
        }
        $comment = $data->comment; 
        if (!isset($comment->comText, $comment->comIdAuthor, $comment->comIdPost)) {
            echo json_encode(["success" => false, "message" => "Paramètres manquants."]);
            exit;
        }
        date_default_timezone_set('Europe/Paris');
        if ($commentModel->insertNewComment(
            $comment->comText, 
            date('Y-m-d H:i:s'), 
            $comment->comIdAuthor, 
            $comment->comIdPost
        )) {
            echo json_encode(["success" => true, "message" => "Ajouté"]);
        } else {
            echo json_encode(["success" => false, "message" => "ratée"]);
        }
    }
?>