<?php 
    // Response
    $response = array();
    // Check if method is delete
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    if($method !== 'delete') {
        $response['error'] = $method . ' is not supported';
        http_response_code(400);
        exit(json_encode($response));
    }
    // Include files
    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
 
    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Project object
    $user = new User($db);

    $username = $_GET['username'] ?? -1;
    if($username === -1) {
        $response['error'] = 'Userame is not found in URL';
        http_response_code(400);
        exit(json_encode($response));
    }
    $user->username = htmlspecialchars(strip_tags($username));

    if(!$user->exists()) {
        $response['error'] = 'User Does Not Exist';
        http_response_code(202);
        exit(json_encode($response));
    }

    if($user->delete()) {
        $response['success'] = 'User Successfully Deleted';
        http_response_code(200);
        exit(json_encode($response));
    }
    $response['error'] = 'Internal Server Error';
    http_response_code(500);
    exit(json_encode($response));
?>