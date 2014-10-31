<?php
    require __DIR__. '/app/database/config.php';
    echo "index.php";

    echo "\n\r";

    $config = new Database();

echo "<pre>";
    var_dump($config);
    echo "</pre>";

    // $user = $config->runQuery("SELECT `email` from `user` where `ID` = 1",array());
    // $row = $config->getArray();
    // printf("%s", $row[0]);

    echo "<br />";

    $data = $config->runQuery("SELECT `email` from `user` where `ID` = 1", array());

    $row1 = $config->getArray($data);

    $ID = 1;
    $bindArray = array(
        'bindTypes' => 'i',
        'bindVariables' => $ID
        );
    $bind = $config->bindQuery("SELECT `email` from `user` where `ID` = ?", $bindArray);
    $row2 = $config->getArray($bind);
    printf("%s", $row2[0]);

    $update = $config->updateQuery("UPDATE `user` SET `email` = 'pista@something.net' WHERE `ID` = 1", array());

