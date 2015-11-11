<?php
include_once '../common/DatabaseConnection.php';

include 'mail.php';
require 'PHPMailerAutoload.php';
$db1 = new DatabaseConnection();
$db1->createConnection();


$res = wwwcopy("todayTask.php");

$sqlC = "select
				s.ScheduleMaintenancePK,
				s.ArtefactCode,
				a.ArtefactName,
				s.ScheduledServiceDate
				from scheduledmaintenance s
				inner join artefact a
				on s.ArtefactCode = a.ArtefactCode
				where  s.ScheduleMaintenancePK
				not in  (select ScheduleMaintenanceFK from tasklist)
				and s.ScheduledServiceDate <= current_date()
				and a.visiblestatus='on'";

$resu1 = $db1->setQuery($sqlC);
if($resu1->num_rows > 0)
{
	$sql1 = "select FirstName,LastName,EmailId
					from user";
	$resu1 = $db1->setQuery($sql1);
	if($resu1->num_rows > 0){
	
		while($r = $resu1->fetch_assoc()) {
	
			$name = $r['FirstName']." ".$r['LastName'];
			$email = $r['EmailId'];
	
			$res1 = str_replace("{{user}}", $name, $res);
			echo sendMail($email, $res1);
		}
	}	
} 



?>