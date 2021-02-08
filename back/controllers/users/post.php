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
    include_once '../../models/User.php';

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
            
    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Project object
    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    $username = '';
    $password = '';
    $email = '';
    // Clean data
    if(!is_null($data)) {
        if(isset($data->username)) {
            $username = htmlspecialchars(strip_tags($data->username));
        } else {
            $response['error'] = 'Username is not set';
            http_response_code(400);
            exit(json_encode($response));
        }
        if(isset($data->password)) {
            $password = htmlspecialchars(strip_tags($data->password));
        }  else {
            $response['error'] = 'Password is not set';
            http_response_code(400);
            exit(json_encode($response));
        }
        if(isset($data->email)) {
            $email = htmlspecialchars(strip_tags($data->email));
        }  else {
            $response['error'] = 'email is not set';
            http_response_code(400);
            exit(json_encode($response));
        }
    } else {
        $response['error'] = 'missing body';
        http_response_code(400);
        exit(json_encode($response));
    }
    

    // Validate data
    if(strlen($username) < 4) {
        $response['error'] = 'Username should be at least 4 characters';
        http_response_code(400);
        exit(json_encode($response));
    }
    if(strlen($password) < 8) {
        $response['error'] = 'Password should be at least 8 characters';
        http_response_code(400);
        exit(json_encode($response));
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['error'] = 'Invalid email address';
        http_response_code(400);
        exit(json_encode($response));
    }

    $user->username = $username;
    $user->password = $password;
    $user->email = $email;

    // Make sure user does not exists
    if($user->exists()) {
        $response['error'] = 'User Already Exists';
        http_response_code(400);
        exit(json_encode($response));
    }

    // Make sure email does not exist
    if($user->emailExists()) {
        $response['error'] = 'Email Already Taken';
        http_response_code(400);
        exit(json_encode($response));
    }


    if($user->create()) {
        $response['success'] = 'User Created';
        http_response_code(201);
        exit(json_encode($response));
    }
    $response['error'] = 'Internal Server Error';
    http_response_code(500);
    exit(json_encode($response));
?>