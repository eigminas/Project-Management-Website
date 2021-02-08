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
    include_once '../../models/ProjectUser.php';

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Project object
    $projectUser = new ProjectUser($db);

    $project_id = $_GET['project_id'] ?? -1;
    $username = $_GET['username'] ?? -1;
    // Case 1: get all project_users
    if($project_id === -1 && $username === -1) {
        $result = $projectUser->getAllProjectUsers();
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

    // Case 2: get all users by project_id
    if($project_id !== -1 && $username === -1) {
        $projectUser->project_id = $project_id;
        $result = $projectUser->getAllUsersByProjectId();
        if($result->rowCount() === 0) {
            $response['error'] = 'No Users Found for this project';
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

    // Case 3: get all projects by username
    if($project_id === -1 && $username !== -1) {
        $projectUser->username = $username;
        $str = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'get.php') + strlen('get.php'));
        $str = substr($str, 1, strpos($str, '?') -2);
        $fields = explode('/', $str);
        $result = $projectUser->getAllByUsername($fields);
        if($result->rowCount() === 0) {
            $response['error'] = 'Not Found';
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

?>