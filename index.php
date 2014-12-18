<?php
    require __DIR__. '/app/database/database.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?></title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
</head>
<body>
    <div id="banner">
        <img src="design/logo_four.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>
    
    <!-- Form for login -->
    <table class="form-middle" >
        <form action = 'login.php' method = 'post' id="form-login">
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


    <!-- Form for registration -->
    <table class="form-middle">
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
    <script type="text/javascript">
    //TO FIX -- DISABLE ONLY REGISTER FORMS SUBMIT BUTTON
//    $(document).ready(function() {
//     var $submit = $("#test"),
//         $inputs = $('#register-form input[type=text], input[type=password]');

//     function checkEmpty() {

//         // filter over the empty inputs

//         return $inputs.filter(function() {
//             return !$.trim(this.value);
//         }).length === 0;
//     }

//     $inputs.on('keyup blur', function() {
//         $submit.prop("disabled", !checkEmpty());
//     }).keyup(); // trigger an initial blur
// });
    </script>
</body>
</html>