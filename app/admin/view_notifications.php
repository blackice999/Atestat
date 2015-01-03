<?php 
    if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
    {
        throw new Exception("Unauthorized access!");
    }

    //Stores which variables should be binded to
    $bindArray = array(
        'bindTypes' => 'i',
        'bindVariables' => array(&$users_array[0])
        );

    $query_status = $db->bindQuery("SELECT `status` FROM `user`
        INNER JOIN `user_status`
        ON `user`.`statusID` = `user_status`.`ID` WHERE `user`.`ID` = ?",
        $bindArray);

    $field = $db->runQuery(
        "SHOW COLUMNS FROM `user_status`",
        array());

    $field_array = $db->getArray($field);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?> - View notifications</title>
    <link rel="stylesheet" type="text/css" href="../../css/main.css">
</head>
<body>
    <table class="user_info" cellpadding="10">
        <thead>
            <?php while ($row = $db->getArray($field)): ?>
                <tr>
                    <th>
                        <?php echo ucfirst($row[0]); ?>
                    </th>
                </tr>
            <?php endwhile; ?>
        </thead>

    </table>
</body>
</html>