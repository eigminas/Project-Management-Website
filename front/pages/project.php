<?php 
    session_start();
    if(!isset($_SESSION['auth'])) {
        header("Location: /playing" );
    }
?>

<!DOCTYPE html>
<html>
<head>
<title>Testing</title>
<link rel="stylesheet" href="../styles/navigation.css">
<link rel="stylesheet" href="../styles/form.css">
<link rel="stylesheet" href="../styles/general.css">
<link rel="stylesheet" href="../styles/project.css">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/5dd4b45025.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="background">
        <div class="container">
            <div class="hide-on-click">
                <nav>
                    <ul>
                        <h2><span class="letter">C</span>ollaborate <span class="letter">D</span>evelope <span class="letter">M</span>anage</h2>
                        <li>
                            <a>Home</a>
                        </li>
                        <li>
                            <a>Connections</a>
                        </li>
                        <li>
                            <a>Account</a>
                        </li>
                        <li>
                            <a>Sign out</a>
                        </li>
                    </ul>
                </nav>
                <div class="title">
                    <h3>Name of the Project</h3>
                </div>

                <div id="collaborators-title-container">
                    <h4>Collaborators</h4>
                </div>

                <div id="collaborators-container">
                    <i class="fas fa-plus" id="add-user"></i>
                </div>
                <hr id="line-1" />
                <div id="icons">
                    <i class="fas fa-sticky-note icon" id="note-icon"></i>
                    <i class="fas fa-file icon" id="file-icon"></i>
                    <i class="fas fa-link icon" id="link-icon"></i>
                </div>
                <div id="notes-files-links">
                    <div id="notes">

                    </div>
                    <div id="files">

                    </div>
                    <div id="links">

                    </div>
                </div>
            </div>
        </div>
        <div class="form-container" id="userform">
            <h2 class="form-heading">Add New User</h2>
            <hr />
            <form id="user-form" method="post" action="#">
              <div class="input-field">
                <label for="username">Username</label><br>
                <input type="text" id="username" name="username" value=""><br>
              </div>
              <div class="buttons">
                <input class="button submit" type="submit" value="Add">
                <button class="button cancel" id="cancel-add-user"><a href="/playing">Cancel</a></button>
              </div>
            </form>
        </div>
        <div class="form-container" id="noteform">
            <h2 class="form-heading">Add New Note</h2>
            <hr />
            <form id="note-form" method="post" action="#">
              <div class="input-field">
                <label for="ntitle">Title</label><br>
                <input type="text" id="ntitle" name="ntitle" value=""><br>
              </div>
              <div class="input-field">
                <label for="nbody">Text</label><br>
                <textarea rows="4" cols="50" name="nbody" id="nbody"></textarea>
              </div>
              <div class="buttons">
                <input class="button submit" type="submit" value="Add">
                <button class="button cancel" id="cancel-add-note"><a href="/playing">Cancel</a></button>
              </div>
            </form>
        </div>
        <div class="form-container" id="linkform">
            <h2 class="form-heading">Add New Link</h2>
            <hr />
            <form id="link-form" method="post" action="#">
              <div class="input-field">
                <label for="lname">Link name</label><br>
                <input type="text" id="lname" name="lname" value=""><br>
              </div>
              <div class="input-field">
                <label for="nbody">Text</label><br>
                <textarea rows="4" cols="50" name="nbody" id="nbody"></textarea>
              </div>
              <div class="buttons">
                <input class="button submit" type="submit" value="Add">
                <button class="button cancel" id="cancel-add-link"><a href="/playing">Cancel</a></button>
              </div>
            </form>
        </div>
    </div>
    <script src="../js/project.js"></script>
</body>
</html>