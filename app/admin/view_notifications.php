<?php 
    if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
    {
        throw new Exception("Unauthorized access!");
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?> - View notifications</title>
    <link rel="stylesheet" type="text/css" href="../../css/main.css">
</head>
<body>

</body>
</html>