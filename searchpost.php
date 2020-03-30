<?php
session_start();
if(isset($_SESSION['sestime']) && (time() - $_SESSION['sestime'] > 600)) {
    session_unset(); 
    session_destroy();
    exit;
}
$_SESSION['sestime'] = time();

include "user.php";
include "userinfo.php";
include "db.php";
include "timeline.php";
include "loginForm.php";
include "error.php";


$login = false;
$user = null;
$queryResults = null;
$errMsg = new Error;

if(isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $login = true;
    $id = $_SESSION['id'];
    $searchKeyword = filter_input(INPUT_POST, "keyword", FILTER_SANITIZE_STRING);            
    $user = unserialize($_SESSION['user']); 
    $profileImgSrc = $user->getProfileImgSrc();    
    $db = new SharepiensDB;
    $timeline = unserialize($_SESSION['timeline']);    
}

function tokenizeKeywords($searchWords) {
    $keywords = array();
    
    $token = strtok($searchWords, " ");              
    while($token !== false) {        
        array_push($keywords, $token);
        $token = strtok(" ");
    }
    
    return $keywords;    
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Search Post </title>
        <link href="css/style.css" rel="stylesheet">                    
    </head>
    <body>
        <?php  
        
        if($login) { // already login, generate timeline
            if( $db->connect()) {                
                $db->searchQuery(tokenizeKeywords($searchKeyword));
                if($db->executeQuery()) {                    
                    $queryResults = $db->getQueryResults();                      
                    $timeline = new Timeline;
                    $timeline->generatePosts($queryResults);                    
                    $timeline->displayTimeline("search");
                    $timeline = new Timeline;                    
                    $_SESSION['timeline'] = serialize($timeline);                                            
                } else {
                    $errMsg->queryExecution();
                }    
            }else {
                $errMsg->databaseConnection();
            }
        } else {  // need to login
            loginForm();
        }

        ?>
    </body>
</html>