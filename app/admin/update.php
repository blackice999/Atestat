<!DOCTYPE html>
<html>
<head>
    <?php require __DIR__ . '/../database/database.php'; ?>
    <title><?php echo $site_name; ?> - Update user</title>
    <link rel="stylesheet" type="text/css" href="../../css/main.css">
</head>
<body>
    <div id="banner">
        <img src="../../design/logo.png">
        <!-- <span><?php echo $site_name; ?></span> -->
    </div>

    <div id="navigation">
         <a href="../../logout.php" id="logout-right">Log out </a>
         <a href="display_users.php" id="logout-right"> Go back </a>
    </div>

</body>
</html>

<?php
    require __DIR__ . '/../database/register.php';
    session_start();

    //Throws an error if there isn't a logged person,
    //and he isn't an admin (having ID 1)
    if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
    {
        throw new Exception("Unauthorized access!");
    }

    //Sanitize the received ID
    //removing all characters except digits, plus and minus sign
    $filter_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    $register = new Register();

    //Create an array which holds the database column names
    //(city, street, zip, country)
    $new_keys = array('city', 'street', 'zip', 'country');

    //Creates a new array, using the $new_keys as key
    //and the POST data as values
    $post_array = array_combine($new_keys, $_POST['Update']);

    //put POST's contents into the register class's variables;
    foreach ($post_array as $key => $value)
    {
        $register->$key = $value;
    }

    //Specify which variables to bind
    $bindArray = array(
        'bindTypes' => 'ssisi',
        'bindVariables' => array(
            &$register->city,
            &$register->street,
            &$register->zip,
            &$register->country,
            &$filter_id
            )
    );
    
    //If there are no address errors (example: the city is valid)
    if ($register->isAddressValid())
    {
        //Update the given user, having ID received by form
        $update = $register->bindQuery(
            "UPDATE `user_address` SET `city` = ?, `street` = ?, `zip` = ?, `country` = ? WHERE `userID` = ?",
            $bindArray
        );
    }

    //If the validation failed, the $update variable won't be set, so this sets it to false
    else
    {
        $update = false;
    }

    //If the validation was successfull, write a notice
    if ($update)
    {
        echo "<p class='text_info'> User successfully updated. <a href='display_users.php'> Go back </a></p>";
    }

    //If there were any validation errors, print them
    else
    {
        if ($register instanceof Register && is_array($register->errors))
        {
            if ($register->errors['hasError'] == true)
            {
                echo "<p class='text_info'> Please fix the following errors: </p>";
                foreach ($register->errors['errors'] as $key => $value)
                {
                    echo "<div class='text_info'>" . $value . "</div>";
                }

                echo "<a href='display_users.php' class='text_info'> Go back </a>";
            }
        }
    }
?>