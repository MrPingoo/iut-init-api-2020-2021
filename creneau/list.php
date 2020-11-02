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

$data = json_decode(file_get_contents("php://input"), true);

// read products will be here
// query products

$stmt = $creneau->list($data['begin'], $data['end']);

$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    $creneaux = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($creneaux as $c) {
        // creneau_matiere
        // $data['matiere']

        // une fonction dans creneau_matiere.php
        // récupération des creneau_matiere dont l'ID de ma matiere = $data['matiere']
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($creneaux);
}else{

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No user found.")
    );
}
