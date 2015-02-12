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
        "SELECT `email`, `user_status`.`status`, `date_registered`
         FROM `user`
         INNER JOIN `user_status`
         ON `user`.`statusID` = `user_status`.`ID` WHERE `user`.`ID` = ?",
        $bindArray);

    //Runs a query to get 'user' table info
    $field = $db->runQuery(
        "SHOW COLUMNS FROM `user` WHERE Field NOT IN ('password','password_hash')",
        array());

    //Get the result of the query
    $field_array = $db->getArray($field);

    //Runs a prepared query to get info from 'user_address' table
    $query_address = $db->bindQuery(
        "SELECT `city`, `street`, `zip`, `country` FROM `user_address` WHERE `userID` = ?",
        $bindArray);

    //Runs a query to get 'user_address' table info
    $field_address = $db->runQuery(
        "SHOW COLUMNS FROM `user_address` WHERE Field NOT IN ('userID')",
        array()
        );

    //Get the result of the query - necessary, because it doesn't include `ID` column
    $info_address = $db->getArray($field_address);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?> - Members page</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/general.js"></script>
</head>
<body>
     <div id="banner">
        <img src="design/logo.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>

    <div id="navigation">
     <a href="logout.php" id="logout-right">Log out </a>
    </div>
    <div id="container">
        <div id="info-left">
            <table class="user_info" cellpadding="10">
         
                <thead>
               <?php  while ($row = $db->getArray($field)): ?>

                    <tr>
                        <th>
                            <?php
                                if ($row[0] == 'statusID')
                                {
                                    $row[0] = 'status';
                                }

                                elseif ($row[0] == 'date_registered')
                                {
                                    $row[0] = 'date registered';
                                }

                                echo ucfirst($row[0]);
                             ?>
                        </th>
                    </tr>

                    <?php endwhile; ?>

                </thead>

                <tbody>

                    <?php while($info_user = $db->getArray($query_user)): ?>

                         <?php foreach ($info_user as $info): ?>
                            <tr>
                                <td> <?php echo $info; ?></td>
                            </tr>

                         <?php endforeach; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h2> Address</h2>
            <table class="user_info" cellpadding="10">
                <thead>
                    <?php while ($row_address = $db->getArray($field_address)): ?>

                        <tr>
                            <th>
                                <?php echo ucfirst($row_address[0]); ?>
                            </th>
                        </tr>

                    <?php endwhile; ?>

                </thead>

                <tbody>
                    <?php while ($info_address = $db->getArray($query_address)): ?>
                        <?php foreach ($info_address as $info): ?>
                            <tr>
                                <td> <?php echo $info; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endwhile; ?>

                </tbody>
                
            </table>
         </div>

        <div id="info-right">
            <?php if ($_SESSION['id'] == 1): ?>
                <h2><a href="app/admin/display_users.php" class="registered"> List registered users</a></h2>
            <?php endif;?>
        </div>
    </div>

</body>
</html>