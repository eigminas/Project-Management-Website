<?php
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

    $method = strtolower($_SERVER['REQUEST_METHOD']);

    switch ($method) {
        case 'get':
            // Get username from url
            $username = $_GET['username'] ?? 0;

            if($username !== 0) {
                $result = $projectUser->getByUsername($username);
                $num = $result->rowCount();
    
                // Check if any projects
                if($num > 0) {
                    // Response
                    $response = array();
                    $response['success'] = array();
                
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        // Push to 'data'
                        array_push($response['success'], $row);
                    }
                    // Turn to JSON & output
                    echo json_encode($response);
                } else {
                    http_response_code(404);
                    echo "Not Found";
                }
            }

            break;
        
        case 'post':
            header('Access-Control-Allow-Methods: POST');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
            
            $data = json_decode(file_get_contents("php://input"));
            $response = array();
            // Create ProjectUser
            if($projectUser->createProjectUser($data)) {
                http_response_code(201);
                $response['success'] = 'Created';
                echo $response;
                
            } else {
              echo json_encode(
                http_response_code(500);
                $response['error'] = 'Not Created';
              );
            }
            break;
        
        case 'delete':
            header('Access-Control-Allow-Methods: DELETE');
            header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
            $username = $_GET['username'] ?? 0;
            $project_id = $_GET['project_id'] ?? 0;
            if($username !== 0 && $project_id !== 0) {
                // Delete project_user
                if($projectUser->deleteProjectUser($username, $project_id)) {
                    echo json_encode(
                      array('message' => 'Project deleted')
                    );
                  } else {
                    echo json_encode(
                      array('message' => 'Project Not Deleted')
                    );
                }   
            } else {
                echo "id is not provided";
            }
            break;
        default:
            echo $method . ' is not supported';
    }
?>