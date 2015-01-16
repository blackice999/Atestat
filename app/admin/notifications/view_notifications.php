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
        "SELECT `ID`,`notes` FROM `user_notes` WHERE `userID` = ?",
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
    <ol class="notes">
        <?php while ($note_info = $db->getArray($notes, MYSQLI_ASSOC)): ?>
            <li class="notes-list">
               
                    <span class="edit-note"> <?php echo $note_info['notes']; ?> </span>

                    <form action="update_notification.php" method="post" class="form-update-note" style="display:none;">
                        <textarea name="note" class="edit-note-input"><?php echo $note_info['notes']; ?></textarea>
                        <input type="hidden" value="<?php echo $note_info['ID'];?>" name="id">
                        <input type="submit" value="Update Note" class="align-center">
                    </form>

                    <a href="delete_notification.php?id=<?php echo $note_info['ID']; ?>"
                        onclick="javascript: return confirm('Are you SURE you wish to delete this note?');">
                    <img src="../../design/red-x.png" title="Remove note" alt="remove" /></a>

                    <span onclick="showNote();" class="edit-user" >
                        <img src="../../design/icon_edit.png" title="Edit note" alt="edit"/>
                    </span>
                    <hr class="notes-splitter" />

                
            </li>
        <?php endwhile; ?>
    </ol>
    <div class="add-note">
        <h3>Add note</h3>
        <form action="add_notification.php" method="post" class="form-notification">
            <textarea name="note"></textarea> <br />
            <input type="hidden" name="userID" value="<?php echo $users_array[0]; ?>">
            <input type="submit" value="Add note" class="align-center">
        </form>
    </div>
</body>
</html>