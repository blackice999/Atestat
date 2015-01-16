<?php
    require __DIR__. '/../database/database.php';
    session_start();

    //Throws an error if there isn't a logged person,
    //and he isn't an admin (having ID 1)
    if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
    {
        throw new  Exception("Unauthorized access!");       
    }

    $db = new Database();

    //Runs a query to return the ID and the email
    //Doesn't return the first registered user (admin)
    $users = $db->runQuery("SELECT `ID`,`email` FROM `user` ORDER BY `ID` ASC LIMIT 1,2147483647", array());
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?> - Registered users</title>
    <link rel="stylesheet" type="text/css" href="../../css/main.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../../js/general.js"></script>
</head>
<body>
    <div id="banner">
        <img src="../../design/logo_four.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>

    <div id="navigation">
         <a href="../../logout.php" id="logout-right">Log out </a>
         <a href="../../members.php" id="logout-right"> Go back </a>
    </div>

    <div id="container-display">

        <div id="info-left-display">
            <?php if (isset($_GET['action']) && $_GET['action'] == 'deleted'): ?>
                <p class="show-error-action" style="position:absolute; top: -15px;"> Successfully deleted user </p>
            <?php endif; ?>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'error'): ?>
                <p class="show-error-action" style="position:absolute; top: -15px;"> Error occured deleting user</p>
            <?php endif; ?>

             <h2> Registered users list</h2>
             <ol style="margin-left: -10px;">
                 <?php while ($users_array = $db->getArray($users, MYSQLI_ASSOC)): ?>

                    <li class='registered-users'>
                        <?php
                            //Get the emails from the query
                            echo $users_array['email'];
                        ?>

                        <span class='users' onclick='showNotifications()'>
                            <?php
                                //Get the emails from the query
                                echo $users_array[1];
                            ?>
                        </span>

                          <div id="show-notifications" class="popup">
                                View notifications
                                <?php require 'notifications/view_notifications.php'; ?>
                                <div class="cancel" onclick="closeNotifications();"></div>
                            </div>

                        <a href="delete_user.php?id=<?php echo $users_array['ID'];?>"
                            onclick="javascript: return confirm('Are you SURE you wish to delete this user?');">
                        <img src="../../design/red-x.png" title="Remove person" alt="remove"/></a>

                        <!-- <a href="update_user.php?id=<?php echo $users_array[0];?>">
                        <img src="../../design/icon_edit.png" title="Edit person" alt="edit"/></a> -->

                         <span onclick="openPopup();" class='edit-user'>
                         <img src="../../design/icon_edit.png" title="Edit person" alt="edit"/></span>
                    
                            <div id="show-popup" class="popup">
                                Update user
                                <?php require 'update_user.php'; ?>
                                <div class="cancel" onclick="closePopup();"></div>
                            </div>
                    </li>

                 <?php endwhile; ?>
             </ol>
        </div>

        <div id="info-right-display">
            <h2> Add a new user </h2>

                <!-- Form for registration -->
                <table class="form-members">
                    <form action = '../../register.php' method = 'post' id="register-form">
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
                            <td colspan="2"><input type = 'submit' value = 'Register' class="align-center"></td>
                        </tr>

                    </form>
                </table>

            <p class='show-error'> Please fill in all form elements </p>
        </div>
    </div>
</body>
</html>