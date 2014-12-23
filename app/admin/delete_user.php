<?php
    require __DIR__ . '/../database/database.php';
    session_start();

    //Throws an error if there isn't a logged person,
    //and he isn't an admin (having ID 1)
    if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
    {
        throw new Exception("Unauthorized access!");       
    } 

    if (!isset($_GET['id']))
    {
        throw new Exception("Missing ID");
    }

    //Sanitize the received ID
    //removing all characters except digits, plus and minus sign
    $filter_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $db = new Database();

    $bindArray = array(
        'bindTypes' => 'i',
        'bindVariables' => array(&$filter_id)
        );

    $delete_user = $db->bindQuery("DELETE FROM `user_address` WHERE `userID` = ?", $bindArray);
    $delete_address = $db->bindQuery("DELETE FROM `user` WHERE `ID` = ? ", $bindArray);
    if ($delete_user && $delete_address)
    {
        header("Location: display_users.php?action=deleted");
        die();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name;?> - Delete user</title>
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