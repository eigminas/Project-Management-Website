<?php
    $response = array();
    // include database (provides connection)
    include_once '../../config/Database.php';
    // include user model (provides functions to manipulate user data)
    include_once '../../models/User.php';

    // General Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();
    // Instantiate User object
    $user = new User($db);
    // Check if method is get
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    if($method !== 'get') {
        $response['error'] = $method . ' is not supported';
        http_response_code(400);
        exit(json_encode($response));
    }
    if($user->logOut()) {
        http_response_code(200);
        $response['success'] = 'User successfully logged out';
        exit(json_encode($response));
    }
    http_response_code(200);
    $response['error'] = 'Internal server error';
    exit(json_encode($response));
?>