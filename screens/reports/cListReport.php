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
          c.CheckListItem,
          ch.Result
        from checklist c
        inner JOIN conditionalreport ch
        on c.CheckListPK = ch.CheckListFK
        where ch.TaskListFK='$task'";

$result = $db->setQuery($sql);
$table="<table class='table table-hover  no-footer clearfix' style='margin: 0px !important;padding: 0px !important;width: 100% !important;min-width: 100% !important;' id='ChReport'><thead><tr><th>Condition</th><th>Result</th></tr></thead>";
$table.="<tbody>";
while($row = $result->fetch_assoc()) {
    $table.="<tr>";
    $table.="<td>$row[CheckListItem]</td>";
    $table.="<td>$row[Result]</td>";
    $table.="</tr>";
}
$table.="</tbody>";
$table.="</table>";

echo $table;