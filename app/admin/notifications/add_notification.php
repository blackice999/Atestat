<!DOCTYPE html>
<html>
<head>
    <?php require __DIR__ . '/../database/database.php'; ?>
    <title><?php echo $site_name;?> - Add note</title>
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
        throw new  Exception("Unauthorized access!");
    }

    $db = new Database();

    //Sanitize the received note string
    $filter_note = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING);

    //Sanitize the received ID
    //removing all characters except digits, plus and minus sign
    $filter_id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_NUMBER_INT);

    //Specify which variables to bind
    $bindArray = array(
        'bindTypes' => 'is',
        'bindVariables' => array(&$filter_id, &$filter_note)
        );

    //Add a new note, with the userID received in id
    $insert_note = $db->bindQuery("INSERT INTO `user_notes` (`userID`, `notes`) VALUES
        (?, ?)",
        $bindArray
    );

    if ($insert_note)
    {
        echo "<p class='text_info'> Note successfully added <a href='display_users.php'> Go back </a></p>";
    }
?>