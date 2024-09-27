<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    
    include_once '../config/database.php';
    include_once '../model/user.php'; 
    
    function register() {
        $data = json_decode(file_get_contents("php://input"));
        //var_dump($data);

        if ($data === null) {
            echo json_encode(["success" => false, "message" => "Aucune donnée reçue."]);
            exit;
        }
            
        $firstname = $data->firstname ?? '';
        $lastname = $data->lastname ?? '';
        $email = $data->mail ?? ''; 
        $password = $data->password ?? '';
        $birthdate = $data->birthdate ?? '';

        if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($birthdate)) {
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
            return;
        }

        $database = new Database(); 
        $db = $database->connect();
        
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        $user = new User($db);
        if ($user->insertUser($firstname, $lastname, $email, $passwordHashed, $birthdate) == true) {
            echo json_encode(['success' => true, 'message' => 'Inscription réussie.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de l\'inscription.']);
        }
    }

    register();

    // TODO : trouver une solution pour la double authentification, l'envoi de code pour confirmer l'inscription
?>
