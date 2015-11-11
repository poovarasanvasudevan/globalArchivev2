<?php
/**
 * Created by PhpStorm.
 * User: poovarasanv
 * Date: 02-11-2015
 * Time: 09:47
 */

require("DatabaseConnection.php");
$db = new DatabaseConnection();
$db->createConnection();
$parent = $_GET['parent'];
$type = $_GET['type'];

$sql = "SELECT *
            FROM artefact
            WHERE ArtefactPID = '$parent' AND VisibleStatus='on' ORDER BY CreatedDate DESC";

//echo $sql;
$result = $db->setQuery($sql);

$resultArray = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $temp["title"] = $row['ArtefactName'];
        $temp["key"] = $row['ArtefactCode'];

        if ($db->isChildAvailable($row['ArtefactName'])) {
            $temp['lazy'] = true;
            $temp['folder'] = true;

        }
        array_push($resultArray, $temp);
    }
    echo json_encode($resultArray);
} else {
    return null;
}