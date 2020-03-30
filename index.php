<?php
session_start();
if(isset($_SESSION['sestime']) && (time() - $_SESSION['sestime'] > 600)) {
    session_unset(); 
    session_destroy();    
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
    $user = unserialize($_SESSION['user']);  
    $db = new SharepiensDB;
    $timeline = unserialize($_SESSION['timeline']);    
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Being Sharepiens! </title>
        <link href="css/style.css" rel="stylesheet">                    
    </head>
    <body>
        <?php  
        
        if($login) { // already login, generate timeline
            if( $db->connect()) {
                $timeline->nextOffset();
                $db->selectQuery(5, $timeline->getCurrentTimelineOffset());                                
                if( $db->executeQuery() ) {
                    $queryResults = $db->getQueryResults();
                    $timeline->generatePosts($queryResults);
                    $timeline->displayTimeline("readmore");
                    $_SESSION['timeline'] = serialize($timeline);
                    
                } else {                
                    $errMsg->executeQuery();
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