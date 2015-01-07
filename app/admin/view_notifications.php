<?php 
    if (!(isset($_SESSION['id']) && $_SESSION['id'] == 1))
    {
        throw new Exception("Unauthorized access!");
    }

    //Stores which variables should be binded to
    $bindArray = array(
        'bindTypes' => 'i',
        'bindVariables' => array(&$users_array[0])
        );

    $query_status = $db->bindQuery("SELECT `status` FROM `user`
        INNER JOIN `user_status`
        ON `user`.`statusID` = `user_status`.`ID` WHERE `user`.`ID` = ?",
        $bindArray);

    $field = $db->runQuery(
        "SHOW COLUMNS FROM `user_status`",
        array());

    $field_array = $db->getArray($field);

    //Get all notes from user_notes table
    $notes = $db->bindQuery(
        "SELECT `notes` FROM `user_notes` WHERE `userID` = ?",
        $bindArray
    );
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?> - View notifications</title>
    <link rel="stylesheet" type="text/css" href="../../css/main.css">
</head>
<body>
    <table class="user_info" cellpadding="10">
        <thead>
            <?php while ($row = $db->getArray($field)): ?>
                <tr>
                    <th>
                        <?php echo ucfirst($row[0]); ?>
                    </th>
                </tr>
            <?php endwhile; ?>
        </thead>

        <tbody>
            <?php while ($info_status = $db->getArray($query_status)): ?>
                <?php foreach ($info_status as $info): ?>
                    <tr>
                        <td class='indent-left'> <?php echo $info; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endwhile; ?>
        </tbody>

    </table>

    <h2>Notes</h2>
    <ol>
        <?php while ($note_text = $db->getArray($notes)): ?>
            <li class="notes-list">
                <?php foreach ($note_text as $note): ?>
                    <span> <?php echo $note; ?> </span>

                    <a href=""
                        onclick="javascript: return confirm('Are you SURE you wish to delete this note?');">
                    <img src="../../design/red-x.png" title="Remove note" alt="remove" /></a>

                    <span onclick="showNotifications();" class="edit-user">
                        <img src="../../design/icon_edit.png" title="Edit note" alt="edit"/>
                    </span>
                <?php endforeach; ?>
            </li>
        <?php endwhile; ?>
    </ol>
    <div class="add-note">
        <h2>Add note</h2>
        <form action="add_note.php" method="post" class="form-notification">
            <textarea name="note"></textarea> <br />
            <input type="submit" value="Add note" class="align-center">
        </form>
    </div>


</body>
</html>