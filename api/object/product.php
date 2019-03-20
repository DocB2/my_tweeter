<?php
class Product{
 
    // database connection and table name
    private $conn;
    private $table_name = "Tweet";
 
    // object properties
    public $id;
    public $user_id;
    public $thead_id;
    public $message;
    public $date;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
// create product
function create(){
 
    // query to insert record
    $query =    "INSERT INTO " . $this->table_name . " 
                SET 
                user_id = :user_id,
                message = :message";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->user_id=htmlspecialchars(strip_tags($this->user_id));
    $this->message=htmlspecialchars(strip_tags($this->message));
 
    // bind values
    $stmt->bindParam(":user_id", $this->user_id);
    $stmt->bindParam(":message", $this->message);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;

}
    // read products
function read(){
 
    // select all query
    $query = "SELECT Tweet.id, User.name, message, date
    FROM " . $this->table_name . " 
    INNER JOIN User ON Tweet.user_id = User.id
    ORDER BY date DESC";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}
}