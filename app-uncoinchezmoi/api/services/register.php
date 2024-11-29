<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../config/database.php';
include_once '../model/user.php';
include_once '../model/host.php';
include_once '../model/guest.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);
$host = new Host($db);
$guest = new Guest($db);

$data = json_decode(file_get_contents('php://input'), true);

if ($data === null) {
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

$id;
$mail = $data['mail'] ?? '';
$password = $data['password'] ?? '';
$firstName = $data['firstName'] ?? '';
$lastName = $data['lastName'] ?? '';
$birthDate = $data['birthDate'] ?? null;
$telephone = $data['telephone'] ?? '';
$gender = $data['gender'] ?? '';
$maritalStatus = $data['maritalStatus'] ?? '';
$type = $data['type'] ?? 'guest';

$passwordHashed = password_hash($password, PASSWORD_DEFAULT);

$userRegistered = registerUser($user, $mail, $passwordHashed, $firstName, $lastName, $birthDate, $gender, $telephone, $maritalStatus, $type);

if ($userRegistered["status"]) {
    $id = $userRegistered["id"];
    $hostCreated = createHostIfNeeded($id, $host, $type);
    
    $guestCreated = createGuestIfNeeded($id, $guest, $type);

    if ($hostCreated || $guestCreated) {
        $message = '';
        if ($type === 'host') {
            $message = "Inscription réussie et hôte créé avec des valeurs NULL.";
        } elseif ($type === 'guest') {
            $message = "Inscription réussie et invité créé avec des valeurs NULL.";
        } else {
            $message = "Inscription réussie.";
        }
        echo json_encode(["success" => true, "message" => $message]);
    } else {
        echo json_encode(["success" => false, "message" => "L'inscription de l'utilisateur a réussi, mais la création de l'hôte ou de l'invité a échoué."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Inscription ratée."]);
}


function registerUser($user, $mail, $passwordHashed, $firstName, $lastName, $birthDate, $gender, $telephone, $maritalStatus, $role)
{
    return $user->insertUser($mail, $passwordHashed, $firstName, $lastName, $birthDate, $gender, $telephone, $maritalStatus, $role);
}

function createHostIfNeeded($id, $host, $type)
{
    if ($type === 'host') {
        return $host->insertHost($id);
    }
    return true;
}

function createGuestIfNeeded($id, $guest, $type)
{
    if ($type === 'guest') {
        return $guest->insertGuest($id);
    }
    return true;
}
