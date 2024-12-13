<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    include_once '../config/database.php';
    include_once '../model/review.php';
    include_once '../model/user.php';

    $database = new Database();
    $db = $database->connect();

    $reviewModel = new Review($db);
    $userModel = new User($db);

    $request = $_SERVER['REQUEST_METHOD'];

    if ($request === "GET") {
        $input = $_GET;       

        if (!isset($input) || (!isset($input) && !isset($input['id'])) ) {
            echo json_encode(["success" => false, "message" => "données d'entrées invalides"]);
            exit;
        }

        //var_dump($input);
        $outputReviews  = $reviewModel->getReviewsByPost($input['id']);
    
        if ($outputReviews) {
            foreach ($outputReviews  as $keys => $values) {
                foreach($values as $key => $value) {
                    if ($key === 'id_user') {
                        $userInfo = $userModel->getUsersById($value);
                        $nameAuthor = $userInfo["firstname"] . ' ' . $userInfo['lastname'];

                        $outputReviews[$keys]["nameAuthor"] = $nameAuthor;
                    }
                }
            }
            echo json_encode(['success' => true, 'reviews' => $outputReviews ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Aucun commentaire trouvé.']);
        }
    } else if ($request === "DELETE") {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($reviewModel->deleteReviewById($data['id'])) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
    } else if ($request === "POST") {
        // ajout d'un commentaire
        $data = json_decode(file_get_contents("php://input"));

        $reviewModel->score = $data->score;
        $reviewModel->comment = $data->content;
        $reviewModel->id_user = $data->idUser;
        $reviewModel->id_post = $data->idPost;
        date_default_timezone_set('Europe/Paris');
        $reviewModel->creation_date = date('Y-m-d H:i:s');

        if ($reviewModel->insertReview()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
    }
?>