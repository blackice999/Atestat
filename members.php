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
    $info_user = $db->getArray($query_user, MYSQLI_ASSOC);

    //Runs a prepared query to get info from 'user_address' table
    $query_address = $db->bindQuery(
        "SELECT `userID`, `city`, `street`, `zip`, `country` FROM `user_address` WHERE `userID` = ?",
        $bindArray);

    //Get the result of the query
    $info_address = $db->getArray($query_address, MYSQLI_ASSOC);
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

        <div id="info-right">
           <table style="text-align: left;" cellpadding="10">
           <tr>
                <th>Email</th>
                <td><?php echo $info_user['email']; ?></td>
           </tr>

            <tr>
                <th>status ID</th>
                <td><?php echo $info_user['statusID']; ?></td>
            </tr>
            <tr>
                <th>Date registered</th>
                <td><?php echo $info_user['date_registered']; ?></td>
           </tr>

           <tr>
               <th>User ID</th>
               <td><?php echo $info_address['userID']; ?></td>
           </tr>

           <tr>
               <th>City</th>
               <td><?php echo $info_address['city']; ?></td>
           </tr>

           <tr>
               <th>Street</th>
               <td><?php echo $info_address['street']; ?></td>
           </tr>

           <tr>
               <th>Zip</th>
               <td><?php echo $info_address['zip']; ?></td>
           </tr>

           <tr>
               <th>Country</th>
               <td><?php echo $info_address['country']; ?></td>
           </tr>
            </table>

        </div>

        <div id="info-left">
        </div>
    </div>
</body>
</html>