<?php 
    // Response
    $response = array();
    // Check if method is post
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    if($method !== 'post') {
        $response['error'] = $method . ' is not supported';
        http_response_code(400);
        exit(json_encode($response));
    }
    // Include files
    include_once '../../config/Database.php';
    include_once '../../models/Link.php';
    include_once '../../models/User.php';

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
            
    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Link object
    $link = new Link($db);

    $data = json_decode(file_get_contents("php://input"));
    if(is_null($data)) {
        $response['error'] = 'Missing body';
        http_response_code(400);
        exit(json_encode($response));
    }
    if(!isset($data->name) || !isset($data->project_id) || !isset($data->author) || !isset($data->url)) {
        $response['error'] = 'Missing values';
        http_response_code(400);
        exit(json_encode($response));
    }
    
    $link->name = htmlspecialchars(strip_tags($data->name));
    if(isset($data->description)) {
        $link->description = htmlspecialchars(strip_tags($data->description));
    }
    $link->author = htmlspecialchars(strip_tags($data->author));
    $link->project_id = htmlspecialchars(strip_tags($data->project_id));
    $link->url = htmlspecialchars(strip_tags($data->url));
    $user = new User($db);
    $user->username = htmlspecialchars(strip_tags($data->author));
    if(!$user->exists()) {
        $response['error'] = $link->author . ' does not exist';
        http_response_code(400);
        exit(json_encode($response));
    }
    ob_start();
    if($link->create()) {
        $response['success'] = 'Link Created';
        $id = ob_get_contents();
        $response['id'] = $id;
        ob_end_clean();
        http_response_code(201);
        exit(json_encode($response));
    }
    $response['error'] = 'Internal Server Error';
    http_response_code(500);
    exit(json_encode($response));
?>