<?php
/*
    I, Kugsang Jeong, 000736736 certify that this material is my original work. 
   No other person's work has been used without due acknowledgement. 
*/

class SharepiensDB {    
    private $dbh;
    private $host;
    private $dbname;
    private $tableName;
    private $id;
    private $pw;
    private $query;
    private $stmt;
    private $result;
    private $err_msg = null;
    private $rows = array(); // to store the results of query execution
        
    function __construct() {
        $this->host = "localhost";
        $this->dbname = "000736736";
        $this->id = "000736736";
        $this->pw = "19741013";
        $this->tableName = "Sharepiens";
    }
    
    function connect() {
        try {
            //$dbh = new PDO("mysql:host=localhost;dbname=000736736", "000736736", "19741013");    
            $this->dbh = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->id, $this->pw);    
            return true;
        } catch (Exception $e) {
            $this->err_msg = $e->getMessage();
            return false;
           // die("ERROR: Couldn't connect. {$e->getMessage()}");
        }
    }
    
    function getErrorMessage() {
        if($this->err_msg != null) {
            return $this->err_msg;
        } else {            
            return "NO ERROR!";
        }
    }
    
    function selectQuery($limit, $offset) {
        $this->query = "SELECT * FROM {$this->tableName} WHERE 1 ORDER BY POST_No DESC LIMIT {$limit} OFFSET {$offset}";
    }
    
    function loadQuery($postNo) {
        $this->query = "SELECT * FROM {$this->tableName} WHERE Post_No = {$postNo}";
    }
    
    function countQuery($postNo) {
        $this->query = "SELECT COUNT(*) FROM {$this->tableName} WHERE Post_No >= {$postNo}";
    }

    function insertQuery($id, $content, $profileImgSrc, $tag) {
        $this->query = "INSERT INTO {$this->tableName} (
            ID, Post_Content, Profile_Image_file, Tag )
            VALUES (
            '{$id}', '{$content}', '{$profileImgSrc}', '{$tag}')";
                
    }    
    
    function searchQuery($keywords) {
        $this->query = "SELECT * FROM {$this->tableName} WHERE ( ";   
        $cnt = 0;
        foreach($keywords as $keyword) {
            if($cnt++ == 0) {
                $this->query .= "Post_Content LIKE '%{$keyword}%' ";
            }
            else {
                $this->query .= "OR Post_Content LIKE '%{$keyword}%' ";
            }
        }        
        $this->query .= ")";
    }
    
    function deleteQuery($postNo) {
        $this->query = "DELETE FROM {$this->tableName} WHERE Post_No = {$postNo}";
    }
    
    function updateQuery($postNo, $id, $content, $profileImgSrc, $tag) {
        $this->query = "UPDATE {$this->tableName} SET Post_Content = '{$content}' WHERE Post_No = {$postNo}";        
    }
    
    function getQueryStatement() {
        return $this->query;
    }
    
    function executeQuery() {
        try {
            $this->stmt = $this->dbh->prepare($this->query);
            if($this->stmt->execute() ) {  
                return true;
            } else {                                
                return false;
            }
        } catch(PDOException $e) {
            $this->err_msg = $e->getMessage();
            return false;
        }
    }
    
    function getQueryResults() {   
        $this->rows = array();
        while($row = $this->stmt->fetch()) {
            array_push($this->rows, $row);             
        }
        
        return $this->rows;
    }
 }
