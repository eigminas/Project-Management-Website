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
    include_once '../../models/Note.php';
    include_once '../../models/User.php';

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
            
    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Note object
    $note = new Note($db);

    $data = json_decode(file_get_contents("php://input"));
    if(is_null($data)) {
        $response['error'] = 'Missing body';
        http_response_code(400);
        exit(json_encode($response));
    }
    if(!isset($data->title) || !isset($data->project_id) || !isset($data->author)) {
        $response['error'] = 'Missing values';
        http_response_code(400);
        exit(json_encode($response));
    }
    
    $note->title = htmlspecialchars(strip_tags($data->title));
    if(isset($data->body)) {
        $note->body = htmlspecialchars(strip_tags($data->body));
    }
    $note->author = htmlspecialchars(strip_tags($data->author));
    $note->project_id = htmlspecialchars(strip_tags($data->project_id));

    $user = new User($db);
    $user->username = htmlspecialchars(strip_tags($data->author));
    if(!$user->exists()) {
        $response['error'] = $note->author . ' does not exist';
        http_response_code(400);
        exit(json_encode($response));
    }
    ob_start();
    if($note->create()) {
        $response['success'] = 'Note Created';
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