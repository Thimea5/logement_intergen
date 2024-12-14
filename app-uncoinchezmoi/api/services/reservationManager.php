<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    include_once '../config/database.php';
    include_once '../model/reservation.php';

    $database = new Database();
    $db = $database->connect();

    $reservationModel = new Reservation($db);

    $requestInfo = $_SERVER['REQUEST_METHOD'];

    if ($requestInfo === "POST") {
        $data = json_decode(file_get_contents('php://input'));

        if ($data === null) {
            echo json_encode(['error' => 'Données invalides']);
            exit;
        }

        $inserted = $reservationModel->insertNewReservation(
            $data->idPost,
            $data->startDate,
            $data->endDate,
            $data->idUser,
            $data->cost
        );

        if ($inserted) {
            echo json_encode(["success" => true, "message" => "Réservation ajoutée avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout de la réservation."]);
        }
    } else if ($requestInfo === "GET") {
        // TODO : ajouter un peu de blindage...
        $inputData = $_GET;
        if (!isset($inputData)) {
            echo json_encode(["success" => false, "message" => "Aucune donnée reçue."]);
            exit;
        }

        if (!isset($inputData['id'])) { 
            echo json_encode(["success" => false, "message" => "Paramètres manquants."]);
            exit;
        }

        $reservations = $reservationModel->getAllReservations();

        $reservationsUsers = $reservationModel->getUserReservations($inputData['id']);

        echo json_encode(["success"=> true, "reservations" => $reservations, "reservationsUsers" => $reservationsUsers]);
    }
   
?>