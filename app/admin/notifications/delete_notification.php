<?php
    require __DIR__ . '/../../database/database.php';
    session_start();

    try
    {
        //Throws an error if there isn't a logged person,
        //and he isn't an admin (having ID 1)
        if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
        {
            header("Location: ../../../error_page.php");
            exit();
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
        if (!$arrayID)
        {
            header("Location: ../display_users.php?action=error");
            die();
        }

        $delete_note = $db->bindQuery("DELETE FROM `user_notes` WHERE `ID` = ?", $bindArray);

        if ($delete_note)
        {
            header("Location: ../display_users.php?action=deleted");
            die();
        }
    }

    catch (Exception $e)
    {
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name;?> - Delete note</title>
    <link rel="stylesheet" type="text/css" href="../../../css/main.css">
</head>
<body>
    <div id="banner">
        <img src="../../../design/logo.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>

    <?php if (isset($_SESSION['id'])): ?>
    <div id="navigation">
         <a href="../../../logout.php" id="logout-right">Log out </a>
         <a href="../display_users.php" id="logout-right"> Go back </a>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['id'])): ?>
    <p class="text_info">
        An error occured: <?php echo $e->getMessage(); ?>
    </p>

    <?php else: ?>

        <p class="text_info">
        An error occured: <?php echo $e->getMessage(); ?>
        <a href="../../../index.php">Go back</a>
        </p>

    <?php endif; ?>
</body>
</html>
<?php } ?>