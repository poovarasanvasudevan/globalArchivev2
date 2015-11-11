<?php
/**
 * Created by PhpStorm.
 * User: poovarasanv
 * Date: 02-11-2015
 * Time: 11:06
 */

require("DatabaseConnection.php");
$db = new DatabaseConnection();
$db->createConnection();
$parent = $_GET['name'];
echo $db->getChildAvailable($parent);