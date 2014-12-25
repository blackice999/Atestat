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

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name;?> - Update user</title>
</head>
<body>

</body>
</html>