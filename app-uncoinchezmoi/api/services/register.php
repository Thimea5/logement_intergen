<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    
    include_once '../config/database.php';
    include_once '../model/user.php';
    
    require 'vendor/autoload.php';
    use \Mailjet\Resources;
    
    function register($firstname, $lastname, $email, $password, $birthdate) {
        
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

    function sendCode($email, $code) {
        
        $mj = new \Mailjet\Client('99a0a9863ce167e3afa60d76554a7370','446ae0bdd979777ea054dfa6e0eaf94d',true,['version' => 'v3.1']);
        $body = [
          'Messages' => [
            [
              'From' => [
                'Email' => "loicfabre0702@gmail.com",
                'Name' => "Loïc Fabre"
              ],
              'To' => [
                [
                  'Email' => $email
                ]
              ],
              'Subject' => "Greetings from Mailjet.",
              'TextPart' => "My first Mailjet email",
              'HTMLPart' => "<h3>Dear passenger 1, welcome to <a href='https://www.mailjet.com/'>Mailjet</a>!</h3><br />May the delivery force be with you!\n tiens, bouffe ce code $code",
              'CustomID' => "AppGettingStartedTest"
            ]
          ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }


    $data = json_decode(file_get_contents("php://input"));

    var_dump($data);

    if ($data === null) {
        echo json_encode(["success" => false, "message" => "Aucune donnée reçue."]);
        exit;
    }
            
    $firstname = $data->firstname ?? '';
    $lastname = $data->lastname ?? '';
    $email = $data->mail ?? '';
    $code = $data->code ?? '';
    $password = $data->password ?? '';
    $birthdate = $data->birthdate ?? '';
    $submit = $data->submit ?? '';

    if ($submit) {
        register($firstname, $lastname, $email, $password, $birthdate);
    } else {
        sendCode($email, $code);
    }

    // TODO : trouver une solution pour la double authentification, l'envoi de code pour confirmer l'inscription
?>
