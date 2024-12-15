<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../config/database.php';
include_once '../model/user.php';
include_once '../model/service.php';
include_once '../model/post.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);
$services = new Service($db);
$post = new Post($db);

$data = $_POST;

if ($data === null) {
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

$email = $data['mail'] ?: '';
$password = password_hash($data['password'] ?: '', PASSWORD_DEFAULT);
$firstName = $data['firstName'] ?: '';
$lastName = $data['lastName'] ?: '';
$birthDate = $data['birthDate'] ?: null;
$gender = $data['gender'] ?: '';
$telephone = $data['telephone'] ?: '';
$maritalStatus = $data['maritalStatus'] ?: '';
$photo = 'lien_photo';  // Initialisation par défaut, cette variable sera mise à jour si une photo est téléchargée

$type = $data['type'] ?: 'guest';

if ($type === 'host') {
    $city = $data['city'] ?: '';
    $postal_code = $data['postal_code'] ?: '';
    $address = $data['address'] ?: '';
    $lat = $data['lat'] ?: 45.7;
    $lng = $data['lng'] ?: 4.8;
    $type_logement = $data['type_logement'] ?: 'Maison';
    $size = $data['size'] ?: 0;
    $description = $data['description'] ?: '';
    $price = $data['price'] ?: 0;
}

$sd = $data['services'] ?: [false, false, false, false, false, false, false, false];

// Insérer l'utilisateur dans la base de données pour obtenir l'ID
$userInserted = $user->insertUser($email, $password, $firstName, $lastName, $birthDate, $gender, $telephone, $maritalStatus, $photo, $type, 1);
$idUser = $userInserted["id"];

if ($userInserted["status"]) {
    // Traitement des fichiers envoyés (photo de profil et images d'hôte)
    if (isset($_FILES['photos']) && is_array($_FILES['photos']['name'])) {
        $files = $_FILES['photos'];

        // Utilisation de l'ID utilisateur pour créer le dossier d'upload
        $uploadDir = '../../front-end/src/assets/img/host' . $idUser . '/post/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);  // Créer le dossier si non existant
        }

        $uploadedFiles = [];

        foreach ($files['name'] as $index => $fileName) {
            // Vérification de l'extension
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                // Renommer le fichier en fonction de l'index (1.jpg, 2.jpg, ...)
                $newFileName = ($index + 1) . '.' . $fileExtension;
                $filePath = $uploadDir . $newFileName;

                // Déplacer le fichier
                if (move_uploaded_file($files['tmp_name'][$index], $filePath)) {
                    $uploadedFiles[] = $filePath;  // Ajouter le chemin du fichier téléchargé
                } else {
                    echo json_encode(["success" => false, "message" => "Erreur lors du téléchargement de l'image"]);
                    exit;
                }
            } else {
                echo json_encode(["success" => false, "message" => "Type de fichier non autorisé"]);
                exit;
            }
        }

        // Mettre à jour la variable photo avec les chemins des fichiers téléchargés
        $photo = implode(',', $uploadedFiles);  // Liste des chemins d'images séparés par des virgules
    }

    // Si l'utilisateur est un 'host', insérer un post et enregistrer les services
    if ($type === 'host') {
        if ($post->insertPost($city, $postal_code, $address, $lat, $lng, "host_photo" . $idUser, $type_logement, $description, $price, $size, 4, 1, $idUser)
            && $services->insertServices($sd[0], $sd[1], $sd[2], $sd[3], $sd[4], $sd[5], $sd[6], $sd[7], $idUser)) {
            echo json_encode(["success" => true, "message" => "Utilisateur créé en tant que host"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur dans l'enregistrement des services"]);
        }
    } else if ($services->insertServices($sd[0], $sd[1], $sd[2], $sd[3], $sd[4], $sd[5], $sd[6], $sd[7], $idUser)) {
        echo json_encode(["success" => true, "message" => "Utilisateur créé en tant que guest"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur dans l'enregistrement des services"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de la création de l'utilisateur"]);
}
