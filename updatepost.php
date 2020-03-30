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
    $postNo = filter_input(INPUT_POST, "postNo", FILTER_VALIDATE_INT);            
    $content = filter_input(INPUT_POST, "content", FILTER_SANITIZE_STRING);
    $user = unserialize($_SESSION['user']); 
    $profileImgSrc = $user->getProfileImgSrc();    
    $tag = "test php";
    $db = new SharepiensDB;
    $timeline = unserialize($_SESSION['timeline']);    
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Update Post </title>
        <link href="css/style.css" rel="stylesheet">                    
    </head>
    <body>
        <?php  
        
        if($login) { // already login, generate timeline
            if( $db->connect()) {                
                $db->updateQuery($postNo,$id, $content, $profileImgSrc, $tag);
                if($db->executeQuery()) {                     
                    $db->countQuery($postNo);
                    if($db->executeQuery()) {
                        $queryResults = $db->getQueryResults();
                        $offset = (int) $queryResults[0];     
                        $timeline = new Timeline;                        
                        $db->selectQuery(5, $offset+1);
                        if( $db->executeQuery() ) {                              
                            $queryResults = $db->getQueryResults();                             
                            $timeline->generatePosts($queryResults);
                            $timeline->displayTimeline("readmore");
                            $_SESSION['timeline'] = serialize($timeline);                        
                        } else {                       
                            $errMsg->QueryExecution();
                        }
                    } else {
                        $errMsg->QueryExecution();
                    }
                } else {
                    $errMsg->QueryExecution();
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