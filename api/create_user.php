<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/my_tweeter/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



// files needed to connect to database
include_once 'config/database.php';
include_once 'object/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);
 
// get posted data
$my_json = file_get_contents("php://input");
$data = json_decode($my_json, TRUE);
 
// set product property values


$user->name = $data['name'];
$user->email = $data['email'];
$user->password = $data['password'];


// create the user
if($user->create()){
    // set response code (le code 200 veut dire "Ok" donc success)
    http_response_code(200);
    // display message: user was created
    echo json_encode(array("message" => "User was created."));
}
// message if unable to create user
else{
    // set response code
    http_response_code(400);
    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}
?>