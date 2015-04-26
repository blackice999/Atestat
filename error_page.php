<?php 
    include 'app/database/database.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?> - Error</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
     <div id="banner">
        <img src="design/logo.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>

    <div id="error-image">
        <img src="design/dolphin_black.png">
        <div id="error-text">
             Oops! An error occured accessing this page. <br /> <a href="index.php">go back </a>
         </div>
    </div>
</body>
</html>