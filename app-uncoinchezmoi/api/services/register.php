<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    include_once '../config/database.php';
    include_once '../model/user.php';

    require '../vendor/autoload.php';    
    
    function register($firstname, $lastname, $email, $password, $birthdate) {
        
        if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($birthdate)) {
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
            return;
        }

        $database = new Database();
        $db = $database->connect();
        
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        $user = new User($db);

        if ($user->insertUser($firstname, $lastname, $email, $passwordHashed, $birthdate)) {
          $userData = $user->getUsersByEmail($email);
            echo json_encode(['success' => true, 'message' => 'Inscription réussie.', "user-info" => $userData]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de l\'inscription.', "user-info" => null]);
        }
    }

    function sendCode($email, $code) {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = "";
        $mail->Password = "";
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;
        $mail->From = "test@testmail.com";
        $mail->FromName = "Letecode";

        $mail->addAddress($email, "recipient name");
        $mail->isHTML(true);
        $mail->Subject = "Mail sent from php send mail script.";
        $mail->Body = "<i>Text content from send mail.</i>";
        $mail->AltBody = "This is the plain text version of the email content";

        try {
            $mail->send();
            echo "Message has been sent successfully";
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }


    $data = json_decode(file_get_contents("php://input"));

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
    } 
    // else {
    //     sendCode($email, $code);
    // }

    // TODO : trouver une solution pour la double authentification, l'envoi de code pour confirmer l'inscription
?>
