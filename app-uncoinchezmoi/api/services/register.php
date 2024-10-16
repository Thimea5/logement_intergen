<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    include_once '../config/database.php';
    include_once '../model/user.php';
  
    
    function register($firstname, $lastname, $email, $password, $birthdate, $genre, $tel, $maritalStatus) {
        
        // if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($birthdate)) {
        //     echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
        //     return;
        // }

        $database = new Database();
        $db = $database->connect();
        
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        $user = new User($db);

        if ($user->insertUser($email, $passwordHashed, $firstname, $lastname,  $birthdate, $genre, $tel, $maritalStatus)) {
          $userData = $user->getUsersByEmail($email);
            echo json_encode(['success' => true, 'message' => 'Inscription réussie.', "user-info" => $userData]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de l\'inscription.', "user-info" => null]);
        }
    }


    $data = json_decode(file_get_contents("php://input"));

    if ($data === null) {
        echo json_encode(["success" => false, "message" => "Aucune donnée reçue."]);
        exit;
    }

            
    $firstname = $data->firstname ?? NULL;
    $lastname = $data->lastname ?? NULL;
    $email = $data->mail ?? NULL;
    $code = $data->code ?? NULL;
    $password = $data->password ?? NULL;
    $birthdate = $data->birthdate ?? NULL;
    $genre = $data->genre ?? NULL;
    $tel = $data->tel ?? NULL;
    $maritalStatus = $data->maritalStatus ?? NULL;

    $submit = $data->submit ?? '';

    var_dump($firstname);
    var_dump($lastname);
    var_dump($email);
    var_dump($code);
    var_dump($password);
    var_dump($birthdate);
    var_dump($genre);
    var_dump($tel);
    var_dump($maritalStatus);

    if ($submit) {
        register($firstname, $lastname, $email, $password, $birthdate, $genre, $tel, $maritalStatus);
    } 

?>
