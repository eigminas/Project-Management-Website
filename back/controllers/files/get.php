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
    include_once '../../models/File.php';

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Project object
    $file = new File($db);

    $id = $_GET['id'] ?? -1;
    $author = $_GET['author'] ?? -1;
    $project_id = $_GET['project_id'] ?? -1;

    // Case 1: get all files
    if($id === -1 && $author === -1 && $project_id === -1) {
        $str = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'get.php') + strlen('get.php'));
        //$str = substr($str, 1, strpos($str, '?') -2);
        $fields = explode('/', $str);
        $result = $file->getAllFiles($fields);
        if($result->rowCount() === 0) {
            $response['error'] = 'No Files Found';
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

    // Case 2: get all files by author
    if($id === -1 && $author !== -1 && $project_id === -1) {
        $str = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'get.php') + strlen('get.php'));
        $str = substr($str, 1, strpos($str, '?') -2);
        $fields = explode('/', $str);
        $file->author = $author;
        $result = $file->getAllFilesByAuthor($fields);
        if($result->rowCount() === 0) {
            $response['error'] = 'No Files Found';
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

    // Case 3: get all files by project_id
    if($id === -1 && $author === -1 && $project_id !== -1) {
        $str = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'get.php') + strlen('get.php'));
        $str = substr($str, 1, strpos($str, '?') -2);
        $fields = explode('/', $str);
        $file->project_id = $project_id;
        $result = $file->getAllFilesByProject($fields);
        if($result->rowCount() === 0) {
            $response['error'] = 'No Files Found';
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