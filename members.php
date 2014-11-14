<?php 
    require __DIR__. '/app/database/database.php';
    session_start();
    
    if (!isset($_SESSION['id']))
    {
        throw new  Exception("Unauthorized access!");       
    }

    $id = $_SESSION['id'];

    $db = new Database();

    //Stores which variables should be binded to
    $bindArray = array(
        'bindTypes' => 'i',
        'bindVariables' => array(&$id)
        );

    //Runs a prepared query to get info from 'user' table
    $query_user = $db->bindQuery(
        "SELECT `email`, `statusID`, `date_registered` FROM `user` WHERE `ID` = ?",
        $bindArray);

    //Get the result of the query
    // $info_user = $db->getArray($query_user, MYSQLI_ASSOC);

    //Runs a prepared query to get info from 'user_address' table
    $query_address = $db->bindQuery(
        "SELECT `userID`, `city`, `street`, `zip`, `country` FROM `user_address` WHERE `userID` = ?",
        $bindArray);

    //Get the result of the query
    $info_address = $db->getArray($query_address, MYSQLI_ASSOC);

    //Runs a query to get table info
    $field = $db->runQuery(
        "SHOW COLUMNS FROM `user` WHERE Field NOT IN ('password','password_hash')",
        array());

    //Get the result of the query
    $field_array = $db->getArray($field)
?>
<!DOCTYPE html>
<html>
<head>
    <title>Members page</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
    <a href="logout.php" id="logout-right">Log out </a>
    <div id="container">
 
       <table id="user_info" cellpadding="10">
     
            <thead>
           <?php  while ($row = $db->getArray($field)): ?>

                <tr>
                    <th>
                        <?php echo $row[0]; ?>
                    </th>
                </tr>

                <?php endwhile; ?>

            </thead>

            <tbody>

                <?php while($info_user = $db->getArray($query_user, MYSQLI_ASSOC)): ?>

                     <?php foreach ($info_user as $info): ?>
                        <tr>
                            <td> <?php echo $info; ?></td>
                        </tr>

                     <?php endforeach; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>