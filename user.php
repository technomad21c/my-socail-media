<?php
class User {
    private $id;
    private $pw;    
    private $profileImgSrc;
    
    function __construct($id, $pw,  $imgSrc) {
        $this->id = $id;
        $this->pw = $pw;        
        $this->profileImgSrc = "images/profile/" . $imgSrc;
    }
    
    function getID() {
        return $this->id;
    }
    function setID($inputId) {
        $this->id = $inputId;
    }
    
    function checkID($inputId) {
        if($this->id === $inputId) {
            return true;
        } else {
            return false;
        }
    }
    
    function getPW() {
        return $this->pw;
    }
    function setPW($inputPw) {
        $this->pw = $inputPw;
    }
    
    function checkPW($inputPw) {
        if($this->pw === $inputPw) {
            return true;
        } else {
            return false;
        }
    }
    
    function getProfileImgSrc() {
        return $this->profileImgSrc;
    }
}

function containID($users, $id) {
    foreach($users as $user) {
        if($user->checkID($id)) {
                return true;    
        }
    }
    
    return false;
}

function correctPW($users, $id, $pw) {
    foreach($users as $user) {
        if($user->getID() === $id && $user->getPW() === $pw) {
            return true;
        }
    }
    return false;
}

