<!DOCTYPE html>
<html>
<head>
    <?php require __DIR__ . '/../database/database.php'; ?>
    <title><?php echo $site_name;?> - Update note</title>
    <link rel="stylesheet" type="text/css" href="../../css/main.css">
</head>
<body>
    <div id="banner">
        <img src="../../design/logo_four.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>

    <div id="navigation">
         <a href="../../logout.php" id="logout-right">Log out </a>
         <a href="display_users.php" id="logout-right"> Go back </a>
    </div>
</body>
</html>

<?php
    session_start();

     //Throws an error if there isn't a logged person,
    //and he isn't an admin (having ID 1)
    if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
    {
        throw new Exception("Unauthorized access!");
    }

    //Sanitize the received textarea string
    $filter_note = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING);

    //Sanitize the received ID
    //removing all characters except digits, plus and minus sign
    $filter_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    $db = new Database();

    //Specify which variables to bind
    $bindArray = array(
        'bindTypes' => 'si',
        'bindVariables' => array(&$filter_note, &$filter_id)
        );

    //Updates the current note by the ID got from the form
    $update = $db->bindQuery(
        'UPDATE `user_notes` SET `notes` = ? WHERE `ID` = ?',
        $bindArray
    );

    if ($update)
    {
        echo "<p class='text_info'> Note successfully updated. <a href='display_users.php'> Go back </a>";
    }

    else
    {
        echo "<p class='text_info'> An error occured uptading the note. <a href='display_users.php'> Go back </a>";
    }
?>