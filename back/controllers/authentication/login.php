<?php
    $response = array();
    // include database (provides connection)
    include_once '../../config/Database.php';
    // include user model (provides functions to manipulate user data)
    include_once '../../models/User.php';

    // General Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();
    // Instantiate User object
    $user = new User($db);

    $method = strtolower($_SERVER['REQUEST_METHOD']);
    if($method !== 'post') {
        $response['error'] = $method . ' is not supported';
        http_response_code(400);
        exit(json_encode($response));
    }

    $data = json_decode(file_get_contents("php://input"));
    if(is_null($data)) {
      $response['error'] = 'Missing body';
      http_response_code(400);
      exit(json_encode($response));
    }

    if(!isset($data->username) || !isset($data->password)) {
      $response['error'] = 'Missing values';
      http_response_code(400);
      exit(json_encode($response));
    }
    // Clean data
    $user->username = htmlspecialchars(strip_tags($data->username));
    $user->password = htmlspecialchars(strip_tags($data->password));
    if($user->authenticate()) {
      // start session
      session_start();
      // set authorized to true
      $_SESSION['auth'] = true;
      // set username in the cookie
      setcookie('user', $user->username, false, "/", false);
      // response success
      $response['success'] = 'User successfully authenticated';
      http_response_code(200);
      echo (json_encode($response));
    } else {
      $response['error'] = 'Invalid credentials';
      echo (json_encode($response));
    }
?>