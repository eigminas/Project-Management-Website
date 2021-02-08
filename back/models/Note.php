<?php
    class Note {
        // DB stuff
        private $conn;
        private $table = 'note';
        private $columns = array('id', 'title', 'body', 'author', 'project_id');

        // Note properties
        public $id;
        public $title;
        public $body;
        public $author;
        public $project_id;

        // Constructor connects to DB
        public function __construct($db) {
            $this->conn = $db;
        }
        
        // Get all notes
        public function getAllNotes($fields) {
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

        // Get all notes by author
        public function getAllNotesByAuthor($fields) {
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

        // Get all notes by project_id
        public function getAllNotesByProject($fields) {
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

        // Create new note
        public function create() {
            $query = 'INSERT INTO ' . $this->table . ' SET title = :title, body = :body,'.
                     ' author = :author, project_id = :project_id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':body', $this->body);
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':project_id', $this->project_id);
            try {
                if($stmt->execute()) {
                    $note_id = $this->conn->lastInsertId($this->table);
                    echo $note_id;
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