<?php
include_once 'ProjectUser.php';
    class Project {
        public $id;
        // DB stuff
        private $conn;
        private $table = 'project';
        private $columns = array('id', 'name', 'description', 'creator');

        // User properties
        public $name;
        public $description;
        public $creator;

        // Constructor connects to DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get all projects
        public function getAllProjects() {
            $query = 'SELECT * FROM ' . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Get project by id
        public function getById() {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            return $stmt;
        }

        public function exists() {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        }

        // Get all projects by creator
        public function getAllProjectsByCreator() {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE creator = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->creator);
            $stmt->execute();
            return $stmt;
        }
        
        // Get all projects by user
        public function getAllProjectsByUser($username, $fields) {
            $str = '';
            foreach($fields as $field) {
                if(in_array($field, $this->columns)) {
                    $str = $str . 'p.' . $field . ', ';
                }
            }
            $str = substr($str, 0, -2);
            $query = 'SELECT ' . $str .' FROM project p, project_user pu WHERE p.id = pu.project_id AND pu.username = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            return $stmt;
        }

        // Create new project
        public function create() {
            $query = 'INSERT INTO ' . $this->table . ' SET name = :name, description = :description,'.
                     ' creator = :creator';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':creator', $this->creator);
            try {
                if($stmt->execute()) {
                    $project_id = $this->conn->lastInsertId($this->table);
                    // Create project_user
                    $projectUser = new ProjectUser($this->conn);
                    $projectUser->username = $this->creator;
                    $projectUser->project_id = $project_id;
                    if($projectUser->create()) {
                        echo $project_id;
                        return true;
                    }
                    return false;
                } else {
                    return false;
                }
            } catch(PDOException $e) {
                echo $e;
                return false;
            }
        }

        // Delete Project
        public function delete() {
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            if($stmt->execute()) {
                if($stmt->rowCount() > 0) {
                    return true;
                }
                else {
                    return false;
                }
            }
            return false;
        }
        // Update project
        public function update($fields) {
            $str = '';
            foreach($fields as $field) {
                $str = $str . $field . ' = :' . $field . ', ';
            }
            $str = substr($str, 0, -2);
            $query = 'UPDATE ' . $this->table . ' SET ' . $str . ' WHERE id = :id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            foreach($fields as $field) {
                $stmt->bindParam(':' . $field, $this->{$field});
            }
            try {
                if($stmt->execute()) {
                    return true;
                }
            } catch(PDOException $e) {
                echo $e;
                return false;
            }
        }

        public function getColumns() {
            return $this->columns;
        }
    }
?>