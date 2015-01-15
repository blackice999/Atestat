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

        //Sanitize the received ID
        //removing all characters except digits, plus and minus sign
        $filter_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        $db = new Database();

        //Specify which variables to bind
        $bindArray = array(
            'bindTypes' => 'i',
            'bindVariables' => array(&$filter_id)
            );

        //Select the element having the received ID
        //if the ID doesn't exist, null is returned
        $valid_id = $db->bindQuery("SELECT `ID` from `user_notes` WHERE `ID` = ?", $bindArray);

        //Gets the result in an array
        $arrayID = $db->getArray($valid_id);

        //If the ID cannot be found,
        //redirect to the previous page, showing an error
        if (!$array_ID)
        {
            header("Location: view_notifications.php?action=error");
            die();
        }
    }
?>