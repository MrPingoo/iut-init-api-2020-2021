<?php
class Creneau{

    // database connection and table name
    private $conn;
    private $table_name = "creneau";

    // object properties
    public $id;
    public $created_at;
    public $updated_at;
    public $nb;
    public $begin;
    public $end;
    public $salle_id = 1;
    public $matieres;

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
                created_at=:created_at,
                updated_at=:updated_at,
                nb=:nb,
                begin=:begin,
                end=:end,
                salle_id=:salle_id
                ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind values
        $date = $this->created_at->format('Y-m-d H:i');
        $stmt->bindParam(":created_at", $date);
        $date = $this->updated_at->format('Y-m-d H:i');
        $stmt->bindParam(":updated_at", $date);
        $stmt->bindParam(":nb", $this->nb);
        $date = $this->begin->format('Y-m-d H:i');
        $stmt->bindParam(":begin", $date);
        $date = $this->begin->format('Y-m-d H:i');
        $stmt->bindParam(":end", $date);
        $stmt->bindParam(":salle_id", $this->salle_id);

        // execute query
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }

        return false;

    }
}
?>