<?php
/**
 * Created by PhpStorm.
 * User: poovarasanv
 * Date: 02-11-2015
 * Time: 10:05
 */

ini_set('max_execution_time', 30000);
include_once 'DatabaseConnection.php';
$type = $_GET['type'];


session_start();

$_SESSION['type'] = $type;
$db = new DatabaseConnection();
$conn = $db->createConnection();
$parentArray = array();//Associate Array with ArtefactCode and ArtefactPID

$sql = "SELECT
            artefactname,
            artefactcode
          FROM artefact
          WHERE artefactPID IS NULL AND artefactTypeCode = '$type' AND visiblestatus = 'on'
          ORDER BY CreatedDate DESC";

//echo $sql;
$result = $db->setQuery($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $temp['title'] = $row['artefactname'];
        $temp['key'] = $row['artefactcode'];

        if ($db->isChildAvailable($row['artefactname']) > 0) {
            $temp['lazy'] = true;
            $temp['folder'] = true;
            // $temp['cc'] = $db->isChildAvailable($row['artefactname']);
        }

        array_push($parentArray, $temp);
    }

    //echo "<pre>";
    //print_r($parentArray);
    echo json_encode($parentArray);
} else {
    return null;
}