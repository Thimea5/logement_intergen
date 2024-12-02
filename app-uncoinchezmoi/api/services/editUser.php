<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    include_once '../config/database.php';
    include_once '../model/user.php';

    $database = new Database();
    $db = $database->connect();

    $userModel = new User($db);

    $requestInfo = $_SERVER['REQUEST_METHOD'];

    if ($requestInfo === "POST") {
        $inputData = json_decode(file_get_contents("php://input"));   

        if (!isset($inputData)) {
            echo json_encode(["success" => false, "message" => "Aucune donnée reçue."]);
            exit;
        }

        $user = $userModel->getUsersById($inputData->id);
        $u = $inputData->usr;
      
        $m1 = ($user['mail'] != $u->mail) ?  $u->mail : $user['mail'];
        $m2 = ($user['firstname'] != $u->firstname) ? $u->firstname : $user['firstname'];
        $m3 = ($user['lastname'] != $u->lastname) ? $u->lastname : $user['lastname'];
        $m4 = ($user['birthdate'] != $u->birthdate) ? $u->birthdate : $user['birthdate'];
        $m5 = ($user['tel'] != $u->tel) ? $u->tel : $user['tel'];

        //var_dump($m1, $m2, $m3, $m4, $m5);
        
        $userModel->updateUser($inputData->id, $m1, $m2, $m3, $m4, $m5);
    }

?>