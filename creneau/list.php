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
$creneau_matiere = new CreneauMatiere($db);

$data = json_decode(file_get_contents("php://input"), true);

// read products will be here
// query products

$stmt = $creneau->list($data['begin'], $data['end']);

$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    $results = [];
    $creneaux = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($creneaux as $c) {
        $stmt_matieres = $creneau_matiere->list($c['id'], $data['matiere']);
        if ($stmt_matieres->rowCount() > 0) {
            $results[] = $c;
        }
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($results);
}else{

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No user found.")
    );
}
