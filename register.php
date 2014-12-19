<!DOCTYPE html>
<html>
<head>
    <?php require __DIR__. '/app/database/database.php'; ?>
    <title><?php echo $site_name; ?> - Register</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
    <div id="banner">
        <img src="design/logo_four.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>
</body>
</html>
<?php 
    require __DIR__.'/app/database/register.php';

    //Prints an error if the redirect didn't come from index.php's form
    if (!isset($_POST['Register']))
    {
        throw new Exception("Unauthorized access!");
    }
    
     $db = new Database();

     if (empty($_POST['Register']))
     {
        $errors[] = 'Some fields are empty, please fill them';
     }

     //If there are no errors, proceed by inserting the new user
     if (empty($errors))
     {

        $register = new Register();

        // put POST's contents into the register class's variables; 
        // $register->attributes = $_POST['Register'];
        foreach ($_POST['Register'] as $key => $value)
        {
            $register->$key = $value;
        } 

        $insert = $register->doRegister($register->email, 1, $register->password);

        if ($insert)
        {
            echo "<p class='text_info'> Registration successful. <a href='index.php'> Go back </a></p>";
        }

        else
        {
            if($register instanceOf Register && is_array($register->errors))
            {
                if ($register->errors['hasError'] == true)
                {
                    echo "<p class='text_info'>please fix the following errors: </p>";
                    foreach ($register->errors['errors'] as $key => $value)
                    {
                        echo "<p class='text_info'>" .$value . "</p>";

                        //Stops the script execution if the email already exists
                        if ($value == 'Email address already exists')
                        {
                            break;
                        }
                    }

                    echo "<a href='index.php' class='text_info'> Go back </a>";
                }
            }
        }
     }

     //If there are errors, print them
     if (isset($errors))
     {
        foreach ($errors as $error)
        {
            echo $error;
        }

        //Also create a back link to index.php
        echo "<a href='index.php'> Go back </a>";
     }
?>