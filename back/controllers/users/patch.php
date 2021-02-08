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
    include_once '../../models/User.php';

    // Headers
    header('Access-Control-Allow-Methods: PATCH');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
 
    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Project object
    $user = new User($db);
    $data = json_decode(file_get_contents("php://input"));

    $username = $_GET['username'] ?? -1;
    if($username === -1) {
        $response['error'] = 'Username is not found in URL';
        http_response_code(400);
        exit(json_encode($response));
    }
    $user->username = htmlspecialchars(strip_tags($username));

    if(!$user->exists()) {
        $response['error'] = 'User Does Not Exist';
        http_response_code(404);
        exit(json_encode($response));
    }

    if(is_null($data)) {
        $response['error'] = 'missing body';
        http_response_code(400);
        exit(json_encode($response));
    }

    if(isset($data->email)) {
        $email = htmlspecialchars(strip_tags($data->email));
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['error'] = 'Invalid email';
            http_response_code(400);
            exit(json_encode($response));
        }
        $user->email = $email;
        if($user->emailExists()) {
            $response['error'] = 'Email Already Taken';
            http_response_code(400);
            exit(json_encode($response));
        }
    } else {
        $response['error'] = 'Missing email';
        http_response_code(400);
        exit(json_encode($response));
    }
    
    if($user->updateEmail()) {
        $response['success'] = 'User Successfully Updated';
        http_response_code(200);
        exit(json_encode($response));
    }
    
    $response['error'] = 'Internal Server Error';
    http_response_code(500);
    exit(json_encode($response));
?>