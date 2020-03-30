<?php

function loginForm() {
    echo '  <br><br><br>
            <div class="title">
                <h1> Share it, Homo Sharepiens! </h1>
            </div>

            <div class="login">
                  <h1>Login</h1>
              <form action="login2.php" method="post">
                  <input type="text" name="id" placeholder="Username" required="required" />
                  <input type="password" name="pw" placeholder="Password" required="required" />
                  <button type="submit" value="login" class="btn">Let Me In</button>
              </form>
                  <br><br>
                  <span style="color:white; font-size:1em;"> Test ID: mohawk1, Test PW: moahwk1 </span>
            </div>';
}
