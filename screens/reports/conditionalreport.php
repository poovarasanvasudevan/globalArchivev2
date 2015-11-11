<?php

include "../common/DatabaseConnection.php";
include '../common/Config.php';
$db = new DatabaseConnection();
$db->createConnection();

$type=$_GET['type'];
$fromDate = date("Y-m-d", strtotime($_GET['fromdate']));
$toDate = date("Y-m-d", strtotime($_GET['todate']));

//echo $fromDate;

$fromDate = date("Y-m-d", strtotime($_GET['fromdate']));
$toDate = date("Y-m-d", strtotime($_GET['todate']));

$sql ="select 
			t.TaskListPK,
			t.ArtefactCode,
  			a.ArtefactName,
			a.ArtefactTypeCode,
			t.ServiceDate,
			l.Description,
			u.FirstName
			from tasklist t
			inner join artefact a
			on t.ArtefactCode = a.ArtefactCode
			inner join archivelocation l 
			on l.LocationPk = t.LocationFK
			inner join user u
			on t.UserFK = u.UserPk
			where t.ServiceDate BETWEEN '$fromDate' AND '$toDate' and a.ArtefactTypeCode='$type'";

$result = $db->setQuery($sql);
if($result->num_rows > 0) {

	$table = "<table class='table table-hover dataTable no-footer clearfix' id='dataTableCR'>";
	$table.="<thead><tr><th>Artefact Code</th><th>Artefact Type</th><th>Location</th><th>Done By</th><th>Date</th><th>View</th></tr></thead>";

	$resultArray = array();
	$table.="<tbody>";
	while($row = $result->fetch_assoc()) {

		$table.="<tr>";
		$table.="
				<td>$row[ArtefactName]</td>
				<td>$row[ArtefactTypeCode]</td>
				<td>$row[Description]</td>
				<td>$row[FirstName]</td>
				<td>".date('d-m-Y',strtotime($row['ServiceDate']))."</td>
				<td><input type='button' id='$row[TaskListPK]' value='View Detail' class='btn btn-primary reportDetail'/></td>
		";

		$table.="</tr>";
	}
	$table.="</tbody>";
	$table.="</table>";
	echo $table;

}else {
	echo "No";
}


?>




