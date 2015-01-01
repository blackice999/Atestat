<?php 
    // require __DIR__ . '/../database/database.php';
    // session_start();

    //Throws an error if there isn't a logged person,
    //and he isn't an admin (having ID 1)
    if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
    {
        throw new Exception("Unauthorized access!");
    }

    // if (!isset($_GET['id']))
    // {
    //     throw new Exception("Missing ID");
    // }

    //Sanitize the received ID
    //removing all characters except digits, plus and minus sign
    $filter_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // $db = new Database();

    //Stores which variables should be binded to
    $bindArray = array(
        'bindTypes' => 'i',
        'bindVariables' => array(&$users_array[0])
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
    <title><?php echo $site_name;?> - Update user</title>
    <link rel="stylesheet" type="text/css" href="../../css/main.css">
</head>
<body>
     <!-- <div id="banner">
        <img src="../../design/logo_four.png"> -->
        <!-- <span><?php echo $site_name; ?></span> -->
   <!--  </div>

    <div id="navigation">
         <a href="../../logout.php" id="logout-right">Log out </a>
         <a href="display_users.php" id="logout-right"> Go back </a>
    </div> -->

   <!--  <div id="container-display">
        <div id="info-left-display"> -->
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
                                <td class="indent-left"> <?php echo $info; ?></td>
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
                            <th class="update-header">
                                <?php echo ucfirst($row_address[0]); ?>
                            </th>
                        </tr>

                    <?php endwhile; ?>

                </thead>

                <form method="post" action="update.php">
                <tbody>

                    <input type="hidden" value="<?php echo $users_array[0];?>" name="id"/>
                    <?php while ($info_address = $db->getArray($query_address)): ?>
                        <?php foreach ($info_address as $info): ?>
                            <tr>
                                <td>
                                    <input type="text" value="<?php echo $info; ?>"
                                    class="user-update" name="Update[]" />
                                 </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endwhile; ?>

                    <tr>
                        <td><input type="submit" value="Update" class="align-center"></td>
                    </tr>

                </tbody>
                </form>
            </table>
        <!--  </div>

        <div id="info-right-display">
        </div>
    </div> -->
</body>
</html>