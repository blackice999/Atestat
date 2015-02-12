<?php
    require __DIR__. '/app/database/database.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?></title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/general.js"></script>
</head>
<body>
    <div id="banner">
        <img src="design/logo.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>
    
    <!-- Form for login -->
    <table class="form-middle" >
        <form action = 'login.php' method = 'post' id="form-login">
            <tr class='align-left'>
                <td>Email:</td>
                <td><input type = 'text' name = 'Login[email]' id = 'email' autofocus/></td>
            </tr>

            <tr class='align-left'>
                <td>Password: </td>
                <td><input type = 'password' name = 'Login[pass]' id = 'pass'/></td>
            </tr>

            <tr>
                <td colspan="2"><input type = 'submit' value = 'Login' class="align-center"></td>
            </tr>

        </form>
    </table>
</body>
</html>