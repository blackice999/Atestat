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
        <img src="design/logo_four.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>

    <a href="logout.php" id="logout-right">Log out </a>
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
            <h2> Add a new user </h2>
            <?php if ($_SESSION['id'] == 1): ?>
                <!-- Form for registration -->
                <table class="form-members">
                    <form action = 'register.php' method = 'post' id="register-form">
                        <tr class='align-left'>
                            <td>Email:</td>
                            <td><input type = 'text' name = 'Register[email]' id = 'email'/></td>
                        </tr>

                        <tr class='align-left'>
                            <td>Password: </td>
                            <td><input type = 'password' name = 'Register[password]' id = 'password'/></td>
                        </tr>

                        <tr class='align-left'>
                            <td>Repeat password: </td>
                            <td><input type = 'password' name = 'Register[password2]' id = 'password2'/></td>
                        </tr>

                        <tr class='align-left'>
                            <td>City:</td>
                            <td><input type='text' name='Register[city]' id='city' /></td>
                        </tr>

                        <tr class='align-left'>
                            <td>Street:</td>
                            <td><input type='text' name='Register[street]' id='street' /></td>
                        </tr>

                         <tr class='align-left'>
                            <td>Zip:</td>
                            <td><input type='text' name='Register[zip]' id='zip' /></td>
                        </tr>

                         <tr class='align-left'>
                            <td>Country:</td>
                            <td><input type='text' name='Register[country]' id='country' /></td>
                        </tr>

                        <tr>
                            <td colspan="2"><input type = 'submit' value = 'Register' class="align-center" id="test"></td>
                        </tr>

                    </form>
                </table>
            <?php endif;?>
        </div>
    </div>

</body>
</html>