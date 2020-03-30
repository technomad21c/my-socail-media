<?php

class Post {
    private $no;
    private $id;
    private $date;    
    private $content;
    private $profile_img_src;
    private $tag;
    
    function __construct($no, $id, $date, $content, $profile_img_src, $tag) {
        $this->no = $no;
        $this->id = $id;
        $this->date = $date;        
        $this->content = $content;
        $this->profile_img_src = $profile_img_src;
        $this->tag = $tag;
    }
    
    function getPostNo() {
        return $this->no;
    }
    
    function getID() {
        return $this->id;
    }
    
    function getDate() {
        return $this->date;
    }
    
    function getContent() {
        return $this->content;
    }
    
    function getProfileImgSrc() {
        return $this->profile_img_src;
    }
    
    function getTag() {
        return $this->tag;
    }
    
    
}

class Timeline {
    private $posts;
    private $editPost;
    private $offset;
    
    
    function __construct() {
        $this->posts = array();
        $this->offset = 0;
    }
    
    function setOffset($offset) {
        $this->offset = $offset;
    }
    
    function nextOffset() {
        $this->offset += 5;
    }
    
    function generatePosts($queryResults) {            
        foreach($queryResults as $result) {            
            $post = new Post($result['Post_No'], $result['ID'], $result['Post_Date'], $result['Post_Content'], $result['Profile_Image_file'], $result['Tag']);
            array_push($this->posts, $post);
        }        
    }
    
    function editPost($result) {
        $this->editPost = new Post($result['Post_No'], $result['ID'], $result['Post_Date'], $result['Post_Content'], $result['Profile_Image_file'], $result['Tag']);        
    }
    
    function deletePost($postNo) {        
        foreach($this->posts as $i => $post) {            
            if($post->getPostNo() == $postNo){
                unset($this->posts[$i]);
                $this->offset -= 1;
                return true;
            }
        }
        
        return false;      
    }
    
    function displayTimeline($type) {        
        $this->printLogoutDiv();

        if($type == "edit") {
            $this->printEditPostDiv();
        } else {
            $this->printSearchDiv();
            $this->printNewPostDiv();
        }
        
        echo '<div class="timeline">';
        $this->printPostDiv();
        echo '</div>';
        if ($type == "readmore") {
            $this->printMoreDiv();
        } else if ($type == "search") {
            $this->printHomeDiv();
        }
      
    }    
    
    function printLogoutDiv() {
        echo '<div id="logout">
                <a href="logout.php"> logout </a>
              </div>';
    }
    
    function printSearchDiv() {
        echo   '<div id="search">
                    <form action="searchpost.php" method="post">
                        <input type="text" name="keyword" placeholder="input searching keywords" required="required" />
                        <button type="submit" value="search"> Search </button> 
                    </form>
                </div>';
    }
        
    function printNewPostDiv() {
        $user = unserialize($_SESSION['user']);
        date_default_timezone_set('America/Toronto');
        echo '<div class="newpost">
                <div>
                    <div class="profile">
                        <img src="' . $user->getProfileImgSrc() . '" class="pImg">    
                        <span class="postname">' . $user->getID() . '</span>
                        <span class="posttime">' . date('Y-m-d H:i:s') . '</span>
                    </div>
                    <div class="content">                
                            <textarea rows="5" cols="30" placeholder="Describe your thinking here..." name="content" form="newpostform" id="posttext"> </textarea>                
                    </div>
                    <div class="insert">
                        <form action="newpost.php" id="newpostform" method="post">
                            <button type="submit" value="insert"> Posting </button>                
                        </form>
                    </div>
                </div>
            </div>';
    }
    
    function printEditPostDiv() {
        //$user = unserialize($_SESSION['user']);
        date_default_timezone_set('America/Toronto');
        echo '<div class="editpost">
                <div>
                    <div class="profile">
                        <img src="' . $this->editPost->getProfileImgSrc() . '" class="pImg">    
                        <span class="postname">' . $this->editPost->getID() . '</span>
                        <span class="posttime">' . date('Y-m-d H:i:s') . '</span>
                    </div>
                    <div class="content">                
                            <textarea rows="10" cols="30" name="content" form="updateform" id="posttext">' . $this->editPost->getContent() .'</textarea>                
                    </div>
                    <div class="update">
                        <form action="updatepost.php" id="updateform" method="post">
                            <button type="submit" value="update"> update </button>                
                            <input type="hidden" name="postNo" value="' . $this->editPost->getPostNo() . '" />
                        </form>
                    </div>
                </div>
            </div>';
    }
    
    function printPostDiv() {
        foreach($this->posts as $post) {
            echo '<div class="post">
                    <div class="profile">
                        <img src="'. $post->getProfileImgSrc(). '" class="pImg">
                        <span class="postname">' . $post->getID() . '</span>
                        <span class="posttime">' . $post->getDate() . '</span>
                    </div>
                    <div class="content">
                        <div class="text">
                            ' . $post->getContent() . ' <br>                            
                        </div>               
                    </div>
                    <div class="delup">
                        <form action="deletepost.php" method="post">
                            <button type="submit" value="delete"> Delete </button>
                            <input type="hidden" name="postNo" value="' . $post->getPostNo() . '" />
                        </form>
                        <form action="editpost.php" method="post">
                            <button type="submit" value="update"> Update </button>
                            <input type="hidden" name="postNo" value="' . $post->getPostNo() . '" />
                        </form>
                    </div>
                </div>';
        }
    }
    
    function printMoreDiv() {        
        echo '<div id="more">
                <form action="index.php" method="post">
                    <button type="submit" value="next"> read more </button>            
                </form>
             </div>';
    }
    
    function printHomeDiv() {
        echo '<div id="more">
                <form action="index.php" method="post">
                    <button type="submit" value="next"> Return to Home </button>            
                </form>
             </div>';

    }
    function getCurrentTimelineOffset() {
        return $this->offset;
    }
}

