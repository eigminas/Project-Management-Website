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
    include_once '../../models/Note.php';

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
 
    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Note object
    $note = new Note($db);

    $id = $_GET['id'] ?? -1;
    if($id === -1) {
        $response['error'] = 'ID is not found in URL';
        http_response_code(400);
        exit(json_encode($response));
    }
    $note->id = htmlspecialchars(strip_tags($id));

    if(!$note->exists()) {
        $response['error'] = 'Note Does Not Exist';
        http_response_code(202);
        exit(json_encode($response));
    }

    if($note->delete()) {
        $response['success'] = 'Note Successfully Deleted';
        http_response_code(200);
        exit(json_encode($response));
    }
    
    $response['error'] = 'Note Was Not Deleted';
    http_response_code(202);
    exit(json_encode($response));
?>