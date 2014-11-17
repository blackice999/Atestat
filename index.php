<?php
    require __DIR__. '/app/database/database.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?></title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
    <div id="banner">
        <img src="design/accountsmall.jpg" width="100" height="50">
        <span>Cool site name</span>
    </div>
    
    <table class="form-middle" >
        <form action = 'login.php' method = 'post'>
            <tr class='align-left'>
                <td>Email:</td>
                <td><input type = 'text' name = 'Login[email]' id = 'email'/></td>
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


    <table class="form-middle" >
        <form action = 'register.php' method = 'post'>
            <tr class='align-left'>
                <td>Email:</td>
                <td><input type = 'text' name = 'Register[email]' id = 'email'/></td>
            </tr>

            <tr class='align-left'>
                <td>Password: </td>
                <td><input type = 'password' name = 'Register[pass]' id = 'pass'/></td>
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
</body>
</html>