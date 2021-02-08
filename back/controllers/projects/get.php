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
    include_once '../../models/Project.php';

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Project object
    $project = new Project($db);

    // Case 1: get all projects
    $id = $_GET['id'] ?? -1;
    $creator = $_GET['creator'] ?? -1;
    $username = $_GET['username'] ?? -1;
    if($id === -1 && $username === -1) {
        $result = $project->getAllProjects();
        if($result->rowCount() === 0) {
            $response['error'] = 'No Projects Found';
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
    // Case 2: get project by id
    if($id !== -1) {
        $project->id = htmlspecialchars(strip_tags($id));
        $result = $project->getById();
        if($result->rowCount() === 0) {
            $response['error'] = 'No Projects Found';
            http_response_code(404);
            exit(json_encode($response));
        }
        $response['success'] = $result->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        exit(json_encode($response));
    }
    // Case 3: get all projects by creator
    if($creator !== -1) {
        $project->creator = htmlspecialchars(strip_tags($creator));
        $result = $project->getAllProjectsByCreator();
        if($result->rowCount() === 0) {
            $response['error'] = 'No Projects Found';
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

    // Case 4: get all projects by username
    if($username !== -1) {
        $str = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'get.php') + strlen('get.php'));
        $str = substr($str, 1, strpos($str, '?') -2);
        $fields = explode('/', $str);

        $result = $project->getAllProjectsByUser($username, $fields);
        if($result->rowCount() === 0) {
            $response['error'] = 'No Projects Found';
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
    $response['error'] = 'Internal Server Error';
    http_response_code(500);
    exit(json_encode($response));
?>