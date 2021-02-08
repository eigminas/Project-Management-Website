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
<link rel="stylesheet" href="../styles/projects.css">
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
                        <li id="signout">
                            <a>Sign out</a>
                        </li>
                    </ul>
                </nav>
                <div class="title">
                    <h3>Your Projects</h3>
                </div>
                <div id="i-container">
                    <i class="fas fa-plus-circle" id="add-new-project"></i>
                </div>
                <article id="projects">
                    
                </article>
            </div>
            <div class="form-container">
                <h2 class="form-heading">Add New Project</h2>
                <hr />
                <form id="project-form" method="post" action="#">
                  <div class="input-field">
                    <label for="pname">Project Name</label><br>
                    <input type="text" id="pname" name="pname" value=""><br>
                  </div>
                  <div class="input-field">
                    <label for="pdesc">Project Description</label><br>
                    <textarea rows="4" cols="50" name="pdesc" id="pdesc"></textarea>
                  </div>
                  <div class="buttons">
                    <input class="button submit" type="submit" value="Add">
                    <button class="button cancel" id="cancel-add-project"><a href="/playing">Cancel</a></button>
                  </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/projects.js"></script>
</body>
</html>