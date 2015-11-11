<?php
/**
 * Created by PhpStorm.
 * User: Poovarasan
 * Date: 11-11-2015
 * Time: 06:41
 */

include '../common/DatabaseConnection.php';

$parent = $_GET['parent'];
$child = $_GET['child'];

$db = new DatabaseConnection();
$db->createConnection();

if ($db->moveArtefact($parent, $child)) {
    echo "success";
} else {
    echo "failed";
}
