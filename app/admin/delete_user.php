<?php
    require __DIR__ . '/../database/database.php'; 
    $id = $_GET['id'];

    echo $id;
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