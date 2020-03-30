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
include "error.php";

$inputID = filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING);
$inputPW = filter_input(INPUT_POST, "pw", FILTER_SANITIZE_STRING);

$db = new SharepiensDB;
$queryResults = null;
$post = null; 
$posts = [];
$timeline = new Timeline;
$errMsg = new Error;

function addUserToSession($users, $id) {
    foreach($users as $user) {
        if($user->getID() === $id) {
            $_SESSION["user"] = serialize($user);
        }            
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sharepiens Timeline</title>
        <link href="css/style.css" rel="stylesheet">                    
    </head>
    <body>
        <?php
            // checking whether ID and PW are valid or not
           if($inputID !== null) {
                if(!containID($users, $inputID)) {
                    $errMsg->wrongLogin();                    
                } else {
                    if($inputPW !== null) {
                        if(!correctPW($users, $inputID, $inputPW)) {
                            $errMsg->wrongLogin();
                        } else {
                            $_SESSION['id'] = $inputID;
                            addUserToSession($users, $inputID);                           
                            if( $db->connect()) {
                                $db->selectQuery(5, $timeline->getCurrentTimelineOffset());                                
                                if( $db->executeQuery() ) {
                                    $queryResults = $db->getQueryResults();
                                    $timeline->generatePosts($queryResults);
                                    $timeline->displayTimeline("readmore");
                                    $_SESSION['timeline'] = serialize($timeline);
                                    
                                } else {                                        
                                    $errMsg->queryExecution(); 
                                    echo $db->getErrorMessage();
                                }
                                
                            } else {
                                $errMsg->databaseConnection();                                
                            }
                                    
                            
                        }
                    } else {
                        $errMsg->inputPW();
                    }
                }
            } else {
                $errMsg->inputID();
            }
                
        ?>
    </body>
</html>
