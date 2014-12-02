<?php 
    session_start();
    require __DIR__. '/app/database/database.php';

if (!isset($_POST['Login']))
{
    throw new  Exception("Unauthorized access!");       
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
    echo "Incorrect username or password. <a href='index.php'> Go back </a>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?> - Login</title>
</head>
<body>

</body>
</html>