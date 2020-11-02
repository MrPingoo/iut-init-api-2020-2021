<?php
class CreneauMatiere {

    // database connection and table name
    private $conn;
    private $table_name = "creneau_matiere";

    // object properties
    public $creneau_id;
    public $matiere_id;
    public $lvl;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // create product
    function create(){

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                creneau_id=:creneau_id,
                matiere_id=:matiere_id,
                lvl=:lvl
                ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":creneau_id", $this->creneau_id);
        $stmt->bindParam(":matiere_id", $this->matiere_id);
        $stmt->bindParam(":lvl", $this->lvl);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;

    }

    // read one user
    function list($creneau_id, $matiere_id){
        // select all query
        $query = "SELECT
                *
            FROM
                " . $this->table_name . " c
            WHERE c.creneau_id = '$creneau_id' AND c.matiere_id = '$matiere_id'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
}
?>