<!DOCTYPE html>
<html>
<head>
    <?php require __DIR__ . '/../database/database.php'; ?>
    <title><?php echo $site_name; ?> - Update user</title>
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
    require __DIR__ . '/../database/register.php';
    session_start();

    //Throws an error if there isn't a logged person,
    //and he isn't an admin (having ID 1)
    if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
    {
        throw new Exception("Unauthorized access!");
    }

    $register = new Register();

    //Create an array which holds the database column names
    //(city, street, zip, country)
    $new_keys = array('city', 'street', 'zip', 'country');

    //Creates a new array, using the $new_keys as key
    //and the POST data as values
    $post_array = array_combine($new_keys, $_POST['Update']);

    foreach ($post_array as $key => $value)
    {
        $register->$key = $value;


    }
?>