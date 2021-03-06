<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate product object
include_once '../objects/creneau.php';
include_once '../objects/creneau_matiere.php';

$globalError = true;

$database = new Database();
$db = $database->getConnection();

$creneau = new Creneau($db);
$creneauMatiere = new CreneauMatiere($db);

// get posted data
$data = json_decode(file_get_contents("php://input"), true);

// make sure data is not empty
if(
    !empty($data['nb']) &&
    !empty($data['begin']) &&
    !empty($data['end']) &&
    !empty($data['matieres'])
){

    // set product property values

    $creneau->nb = $data['nb'];
    $creneau->begin = DateTime::createFromFormat('Y-m-d H:i', $data['begin']);
    $creneau->end = DateTime::createFromFormat('Y-m-d H:i', $data['end']);
    $creneau->created_at = new DateTime();
    $creneau->updated_at = new DateTime();

    $creneau_id = $creneau->create();

    if ($creneau_id != false) {
        $globalError = false;

        foreach ($data['matieres'] as $m) {
            $cm = new CreneauMatiere($db);
            $cm->creneau_id = $creneau_id;
            $cm->lvl = 10;
            $cm->matiere_id = $m;

            if ($cm->create()) {
                $globalError = false;
            } else {
                $globalError = true;
            }
        }
    }

    if(!$globalError){

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Créneau créé."));
    }

    // if unable to create the product, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create students and user."));
    }
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}
?>