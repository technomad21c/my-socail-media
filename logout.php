<?php
session_start();

include "loginForm.php";
include "error.php";

$errMsg = new Error;

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
            if( session_destroy() ) {
                loginForm();                    
            } else {
                $errMsg->destroySession();
            }
        ?>
    </body>
</html>