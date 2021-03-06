<?php 
    session_start();
    require __DIR__. '/app/database/database.php';

if (!isset($_POST['Login']))
{
    header("Location: error_page.php");
    exit();
}

$db = new Database();
$data = $db->authorizeAccess($_POST['Login']['email'], $_POST['Login']['pass']);

//if all good, redirect to the account section
if ($data)
{
    $_SESSION['id'] = $data;
    header('Location: members.php');
    die();
}

else
{
    echo "<p class='text_info'> Incorrect username or password. <a href='index.php'> Go back </a></p>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?> - Login</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
     <div id="banner">
        <img src="design/logo.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>
</body>
</html>