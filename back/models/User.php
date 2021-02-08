<?php
    class User {
        // DB stuff
        private $conn;
        private $table = 'user';

        // User properties
        public $username;
        public $password;
        public $email;

        // Constructor connects to DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get all users
        public function getAllUsers() {
            $query = 'SELECT * FROM ' . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Get user by username
        public function getByUsername() {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE username = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->username);
            $stmt->execute();
            return $stmt;
        }

        public function exists() {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE username = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->username);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        }

        public function emailExists() {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE email = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->email);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        }

        // Create new user
        public function create() {
            $query = 'INSERT INTO ' . $this->table . ' SET username = :username, password = :password,'.
                     ' email = :email';
            $stmt = $this->conn->prepare($query);
            // Hash password
            $hash = password_hash($this->password, PASSWORD_BCRYPT);
            // Bind data
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':password', $hash);
            $stmt->bindParam(':email', $this->email);
            try {
                if($stmt->execute()) {
                    return true;
                }
            } catch(PDOException $e) {
                echo $e;
                return false;
            }
        }

        // Delete user
        public function delete() {
            $query = 'DELETE FROM ' . $this->table . ' WHERE username = :username';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $this->username);
            // Execute query
            if($stmt->execute()) {
              return true;
            }
            echo $stmt->error;
            return false;
        }
        
        // Update user
        public function updateEmail() {
            $query = 'UPDATE ' . $this->table . ' SET email = :email WHERE username = :username';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':username', $this->username);
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

        // Authenticate user
        public function authenticate() {
            $response = array();
            $query = 'SELECT * FROM ' . $this->table . ' WHERE username = ?';
            $stmt = $this->conn->prepare($query);
            // Bind id
            $stmt->bindParam(1, $this->username);
            // Execute query
            $stmt->execute();

            // Check if user exists
            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // Check if passwords match
                if(password_verify($this->password, $row['password'])) {
                    return true;
                }
                else {
                    return false;
                }
            } else {
                return false;
            }
        }

        // Log out user
        public function logOut() {
            session_start();
            session_unset();
            session_destroy();
            return true;
        }
    }
?>