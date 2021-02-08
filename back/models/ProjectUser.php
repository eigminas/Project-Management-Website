<?php
    class ProjectUser {
        // DB stuff
        private $conn;
        private $table = 'project_user';

        // project_user properties
        public $username;
        public $project_id;

        // Constructor connects to DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get all project_users
        public function getAllProjectUsers() {
            // Create query
            $query = 'SELECT * FROM ' . $this->table;
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get all projects by username
        public function getAllByUsername($fields) {
            include_once 'Project.php';
            $project = new Project($this->conn);
            $columns = $project->getColumns();
            $str = ', ';
            foreach($fields as $field) {
                if(in_array($field, $columns)) {
                    $str = $str . 'p.' . $field . ', ';
                }
            }
            $str = substr($str, 0, -2);
            // Create query
            $query = 'SELECT pu.username' . $str . ' FROM project_user pu INNER JOIN project p ON pu.project_id = p.id WHERE pu.username = :username';
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind data
            $stmt->bindParam(':username', $this->username);
            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get all users by project_id
        public function getAllUsersByProjectId() {
            // Create query
            $query = 'SELECT username FROM ' . $this->table . ' WHERE project_id = :project_id';
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind data
            $stmt->bindParam(':project_id', $this->project_id);
            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Create project_user
        public function create() {
            $query = 'INSERT INTO ' . $this->table . ' SET username = :username, project_id = :project_id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':project_id', $this->project_id);
            // Execute query
            try {
                if($stmt->execute()) {
                    return true;
                }
            } catch(PDOException $e) {
                echo $e;
                return false;
            }
        }

        // Delete project_user
        public function DeleteProjectUser($username, $project_id) {
            // Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE username = :username AND project_id = :project_id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $username = htmlspecialchars(strip_tags($username));

            // Bind data
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':project_id', $project_id);

            // Execute query
            if($stmt->execute()) {
              return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }
?>