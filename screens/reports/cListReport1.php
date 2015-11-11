<?php
/**
 * Created by PhpStorm.
 * User: poovarasanv
 * Date: 28-08-2015
 * Time: 11:14
 */

include "../common/DatabaseConnection.php";

$db = new DatabaseConnection();
$db->createConnection();

$task = $_GET['task'];

$sql = "select
          a.ArtefactName,
          c.CheckListItem,
          ch.Result
        from checklist c
        inner JOIN conditionalreport ch
        on c.CheckListPK = ch.CheckListFK
        INNER JOIN tasklist t
        ON t.TaskListPK = ch.TaskListFK
        INNER join artefact a
        on a.ArtefactCode = t.ArtefactCode
        where ch.TaskListFK='$task';";

$result = $db->setQuery($sql);
$r = array();
while($row = $result->fetch_assoc()) {

    $r[] = $row;
}

echo json_encode($r);