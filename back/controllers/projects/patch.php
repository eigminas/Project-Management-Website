<?php 
    // Response
    $response = array();
    // Check if method is patch
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    if($method !== 'patch') {
        $response['error'] = $method . ' is not supported';
        http_response_code(400);
        exit(json_encode($response));
    }
    // Include files
    include_once '../../config/Database.php';
    include_once '../../models/Project.php';
    include_once '../../models/User.php';

    // Headers
    header('Access-Control-Allow-Methods: PATCH');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
 
    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Project object
    $project = new Project($db);
    $data = json_decode(file_get_contents("php://input"));

    $id = $_GET['id'] ?? -1;
    if($id === -1) {
        $response['error'] = 'ID is not found in URL';
        http_response_code(400);
        exit(json_encode($response));
    }
    $project->id = htmlspecialchars(strip_tags($id));
    
    if(!$project->exists()) {
        $response['error'] = 'Project Does Not Exist';
        http_response_code(202);
        exit(json_encode($response));
    }
    if(is_null($data)) {
        $response['error'] = 'Missing body';
        http_response_code(400);
        exit(json_encode($response));
    }
    $fields = array();
    if(isset($data->name)) {
        $project->name = htmlspecialchars(strip_tags($data->name));
        array_push($fields, 'name');
    }
    if(isset($data->description)) {
        $project->description = htmlspecialchars(strip_tags($data->description));
        array_push($fields, 'description');
    }
    if(isset($data->creator)) {
        $project->creator = htmlspecialchars(strip_tags($data->creator));
        $user = new User($db);
        $user->username = htmlspecialchars(strip_tags($data->creator));
        if(!$user->exists()) {
            $response['error'] = $project->creator . ' does not exist';
            http_response_code(400);
            exit(json_encode($response));
        }
        array_push($fields, 'creator');
    }

    if(count($fields) === 0) {
        $response['error'] = 'No fields given';
        http_response_code(400);
        exit(json_encode($response));
    }
    
    if($project->update($fields)) {
        $response['success'] = 'Project Successfully Updated';
        http_response_code(200);
        exit(json_encode($response));
    }
    $response['error'] = 'Project Was Not Updated';
    http_response_code(202);
    exit(json_encode($response));
?>