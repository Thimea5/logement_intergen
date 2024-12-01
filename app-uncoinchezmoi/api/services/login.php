<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json; charset=UTF-8");

    require '../vendor/autoload.php'; 
    include_once '../config/database.php';
    include_once '../model/user.php';
    include_once '../model/service.php';

    use Firebase\JWT\JWT;

    $secretKey ='uR7o^a#xP9kDfJbLqZ2w@vT5kLmX3mZ4'; 
    
    $data = json_decode(file_get_contents("php://input"));    

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
    $service = new Service($db);

    // Recherche de l'utilsateur en base 
    $userData = $user->getUsersByEmail($data->email);
    //var_dump($userData);

    if ($userData === false) {
        echo json_encode(["success" => false, "message" => "Utilisateur non trouvé."]);
        exit;
    } else {
        //var_dump(password_verify($data->password, $userData['password']));
        if (password_verify($data->password, $userData['password'])) {
            $payload = [
                'iat' => time(), 
                'exp' => time() + (60 * 60), // 1 heure
                'usr-id' => $userData['id'], 
                'usr-mail' => $userData['mail']
            ];

            $jwt = JWT::encode($payload, $secretKey, 'HS256');

            $servicesData = $service->getServicesByHost($userData['id']);

            echo json_encode([
                "success" => true, 
                "message" => "Connexion réussie.", 
                "token" => $jwt, 
                "user-info" => $userData,
                "user-services" => $servicesData
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Mot de passe incorrect."]);
        }
    }
?>
