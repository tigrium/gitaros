<?php

include 'common.php';
        
//echo 'name:' . $POST['name'];

//$arr = array('post' => $_POST);
//echo json_encode($arr);

//$res = $db->query("SELECT * FROM Users;");
$res = $db->query("SELECT name, team FROM Users WHERE name = '" . $_POST['name'] . "' AND passwd = '" . $_POST['passwd'] . "';");

echo $res;