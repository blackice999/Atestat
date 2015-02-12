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

        $valid_id = $db->bindQuery("SELECT `ID` FROM `user` WHERE `ID` = ?", $bindArray);

        $arrayID = $db->getArray($valid_id);

        if (!$arrayID)
        {
            header("Location: display_users.php?action=error");
            die();
        }

        $delete_user = $db->bindQuery("DELETE FROM `user_address` WHERE `userID` = ?", $bindArray);
        $delete_address = $db->bindQuery("DELETE FROM `user` WHERE `ID` = ? ", $bindArray);
        if ($delete_user && $delete_address)
        {
            header("Location: display_users.php?action=deleted");
            die();
        }
    }

    catch (Exception $e)
    {
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?> - Delete user</title>
    <link rel="stylesheet" type="text/css" href="../../css/main.css">
</head>
<body>
    <div id="banner">
        <img src="../../design/logo.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>

    <?php if (isset($_SESSION['id'])): ?>
    <div id="navigation">
         <a href="../../logout.php" id="logout-right">Log out </a>
         <a href="../../members.php" id="logout-right"> Go back </a>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['id'])): ?>
    <p class="text_info">
        An error occured: <?php echo $e->getMessage(); ?>
    </p>

    <?php else: ?>

        <p class="text_info">
        An error occured: <?php echo $e->getMessage(); ?>
        <a href="../../index.php">Go back</a>
        </p>

    <?php endif; ?>
</body>
</html>
<?php } ?>