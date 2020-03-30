<?php

class Error {
    function wrongLogin() {
        echo '<h1 class="title"> Ooops... </h1>';
        echo '<p class="error"> Wrong ID or Password!  </p>';
        echo '<p class="error"> if you want to try again, click <a href="index.php"> this </a></p>';        
    }
    
    function inputID() {
        echo '<h1 class="title"> Ooops... </h1>';
        echo '<p class="error"> please input your id!</p>';
    }
    
    function inputPW() {
        echo '<h1 class="title"> Ooops... </h1>';                    
        echo '<p class="error"> please input your password! </p>';        
    }
    
    
    function queryExecution() {
        echo '<h1 class="title"> Query Execution Error !  </h1>';    
        echo '<p class="error"> if you want to try again, click <a href="index.php"> this </a></p>';
        session_destroy();
    }
    
    function databaseConnection() {
        echo '<h1 class="title"> Database Connection Error!  </h1>';    
        echo '<p class="error"> if you want to try again, click <a href="index.php"> this </a></p>';
        session_destroy();
    }
    
    function destroySession() {
        echo '<h1 class="title"> Session Destruction Error!  </h1>';    
        echo '<p class="error"> if you want to try again, click <a href="logout.php"> this </a></p>';
        session_destroy();
    }
}
