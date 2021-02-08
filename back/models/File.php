<?php
    class File {
        // DB stuff
        private $conn;
        private $table = 'file';
        private $columns = array('id', 'name', 'description', 'author', 'project_id');

        // Note properties
        public $id;
        public $name;
        public $description;
        public $author;
        public $project_id;

        // Constructor connects to DB
        public function __construct($db) {
            $this->conn = $db;
        }
        
        // Get all files
        public function getAllFiles($fields) {
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

        // Get all files by author
        public function getAllFilesByAuthor($fields) {
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

        // Get all files by project_id
        public function getAllFilesByProject($fields) {
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
    }
?>