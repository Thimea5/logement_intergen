<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require '../model/config.php';
    require '../model/userRepository.php';
    

    //echo json_encode($users);

    const PATTERN_EMAIL = "^[\w\-\.]+@([\w-]+\.)+[\w-]{2,}$"; // ? check en front

    // Enregistre en base l'utilisateur
    function register() {
        // Recevoir tout les champs du formulaire
        // TODO faire le insert
    }

    // 
    function sendRegisterEmailWithCode($to) {
        // TODO : change mail envoi, html body pour un peu de style du mail
        $code = rand(100000, 999999);
        
        $subject = "Verify your email adress";
        $message =  "code : " + $code;

        $headers = array(
            'From' => 'guillaume.hostache@univ-lyon2.fr',
            'Reply-To' => 'guillaume.hostache@univ-lyon2.fr',
            'X-Mailer' => 'PHP/' . phpversion()
        );

        mail($to, $subject, $message, $headers);
    }
?>
