<?php 
    // Response
    $response = array();
    // Check if method is get
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    if($method !== 'get') {
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

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Project object
    $user = new User($db);

    // Case 1: get all users
    $username = $_GET['username'] ?? -1;
    if($username === -1) {
        $result = $user->getAllUsers();
        if($result->rowCount() === 0) {
            $response['error'] = 'No Users Found';
            http_response_code(404);
            exit(json_encode($response));
        }
        $response['success'] = array();
            
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($response['success'], $row);
        }
        http_response_code(200);
        exit(json_encode($response));
    }
    // Case 2: get user by username
    if($username !== -1) {
        $user->username = htmlspecialchars(strip_tags($username));
        $result = $user->getByUsername();
        if($result->rowCount() === 0) {
            $response['error'] = 'User Not Found';
            http_response_code(404);
            exit(json_encode($response));
        }
        $response['success'] = $result->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        exit(json_encode($response));
    }

    $response['error'] = 'Internal Server Error';
    http_response_code(500);
    exit(json_encode($response));
?>