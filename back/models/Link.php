<?php
    class Link {
        // DB stuff
        private $conn;
        private $table = 'link';
        private $columns = array('id', 'name', 'description', 'author', 'project_id', 'url');

        // Note properties
        public $id;
        public $name;
        public $description;
        public $author;
        public $project_id;
        public $url;

        // Constructor connects to DB
        public function __construct($db) {
            $this->conn = $db;
        }
        
        // Get all links
        public function getAllLinks($fields) {
            $str = '';
            foreach($fields as $field) {
                if(in_array($field, $this->columns)) {
                    $str = $str . $field . ', ';
                }
            }
            $str = substr($str, 0, -2);
            if(strlen($str) === 0) {
                $str = '*';
            }
            $query = 'SELECT ' . $str . ' FROM ' . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Get all links by author
        public function getAllLinksByAuthor($fields) {
            $str = '';
            foreach($fields as $field) {
                if(in_array($field, $this->columns)) {
                    $str = $str . $field . ', ';
                }
            }
            $str = substr($str, 0, -2);
            if(strlen($str) === 0) {
                $str = '*';
            }
            $query = 'SELECT ' . $str . ' FROM ' . $this->table . ' WHERE author = :author';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':author', $this->author);
            $stmt->execute();
            return $stmt;
        }

        // Get all links by project_id
        public function getAllLinksByProject($fields) {
            $str = '';
            foreach($fields as $field) {
                if(in_array($field, $this->columns)) {
                    $str = $str . $field . ', ';
                }
            }
            $str = substr($str, 0, -2);
            if(strlen($str) === 0) {
                $str = '*';
            }
            $query = 'SELECT ' . $str . ' FROM ' . $this->table . ' WHERE project_id = :project_id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':project_id', $this->project_id);
            $stmt->execute();
            return $stmt;
        }

        // Create new link
        public function create() {
            $query = 'INSERT INTO ' . $this->table . ' SET name = :name, description = :description,'.
                     'url = :url, author = :author, project_id = :project_id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':project_id', $this->project_id);
            $stmt->bindParam(':url', $this->url);
            try {
                if($stmt->execute()) {
                    $link_id = $this->conn->lastInsertId($this->table);
                    echo $link_id;
                    return true;
                } else {
                    return false;
                }
            } catch(PDOException $e) {
                echo $e;
                return false;
            }
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

        // Delete Note
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
    }
?>