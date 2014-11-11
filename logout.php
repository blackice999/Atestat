<?php 
    //Starts a new session and destroys it,
    //redirecting to index page
    session_start();
    session_destroy();
    header("Location: index.php");
?>