<?php

include "../common/DatabaseConnection.php";
$db = new DatabaseConnection();
$db->createConnection();

$user = $_GET['userpk'];

$sql = "update user set ActiveStatus='on' where userpk='$user'";

if($db->setQuery($sql)) {
	echo "success";
} else {
	echo "fail";
}