<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    include_once '../config/database.php';
    include_once '../model/reservation.php';

    $database = new Database();
    $db = $database->connect();

    $reservationModel = new Reservation($db);


    $data = json_decode(file_get_contents('php://input'), associative: true);

    if ($data === null) {
        echo json_encode(['error' => 'Données invalides']);
        exit;
    }

    //var_dump($data);

    $res = $reservationModel->insertReservation($data['idPost'], $data['idUser']);
    echo json_encode(["success"=>$res]);
?>