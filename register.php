<?php 
    require __DIR__. '/app/database/database.php';
    require __DIR__.'/app/database/register.php';

    if (!isset($_POST['Register']))
    {
        throw new  Exception("Unauthorized access!");       
    }

     $db = new Database();

     echo "<pre>";
     print_r($_POST['Register']);
     echo "</pre>";

     if (empty($_POST['Register']))
     {
        $errors[] = 'Some fields are empty, please fill them';
     }

     if (!filter_var($_POST['Register']['email'], FILTER_VALIDATE_EMAIL))
     {
        $errors[] = 'Please enter a correct e-mail address';
     }

     if (empty($errors))
     {
        $register = new Register();
        // put POST's contents into the register class's variables; 
        // $register->attributes = $_POST['Register'];
        foreach ($_POST['Register'] as $key => $value)
        {
            $register->$key = $value;
        } 

        // $insert = $register->doRegister($email, 1, $password);

        if ($insert)
        {
            echo "Registration successful. <a href='index.php'> Go back </a>";
        }

        else
        {
            echo "User already exists. <a href='index.php'> Go back </a>";
        }
     }

     if (isset($errors))
     {
        foreach ($errors as $error)
        {
            echo $error;
        }
        echo "<a href='index.php'> Go back </a>";
     }
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?> - Register</title>
</head>
<body>

</body>
</html>