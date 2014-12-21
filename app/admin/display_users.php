<?php
    require __DIR__. '/../database/database.php';
    session_start();

    if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
    {
        throw new  Exception("Unauthorized access!");       
    }

    $db = new Database();

    $users = $db->runQuery("SELECT `email` FROM `user` ORDER BY `email` DESC", array());
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?> - Registered users</title>
    <link rel="stylesheet" type="text/css" href="../../css/main.css">
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

    <h2 id="add-user"> Add a new user </h2>

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

     <ol>
         <?php while ($users_array = $db->getArray($users)): ?>

            <?php foreach ($users_array as $user): ?>
                <li class="text_info">
                    <?php echo $user; ?>
                    <a href=""><img src="../../design/red-x.png" title="Remove person" alt="remove"/></a>
                    <a href=""><img src="../../design/icon_edit.png" title="Edit person" alt="edit" /></a>
                </li>
            <?php endforeach; ?>

         <?php endwhile; ?>
     </ol>
</body>
</html>