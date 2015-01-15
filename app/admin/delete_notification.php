<?php
    require __DIR__ . '/../database/database.php';
    session_start();

    try
    {
        //Throws an error if there isn't a logged person,
        //and he isn't an admin (having ID 1)
        if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
        {
            throw new Exception("Unauthorized access!");
        }

        //Throws an error if there is no ID in the link
        if (!isset($_GET['id']))
        {
            throw new Exception("Missing ID");
        }
    }
?>