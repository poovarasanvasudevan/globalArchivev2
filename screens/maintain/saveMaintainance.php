<?php
session_start();
include 'DatabaseConnection.php';

//conversion dd-mm-yyy to yyyy-mm-dd 
$currentdate = date('Y-m-d', strtotime($_GET['currentDate'])); ;
$nextDate = date('Y-m-d', strtotime($_GET['nextDate'])); ;



$lastDate=$_GET['endDate'];
$freq= $_GET['freq'];
$units=$_GET['units'];
$day = $_GET['day'];

$artefactCode = $_SESSION['seesionSelectedCode'];
$artefactType = $_SESSION['type'];
$location = $_SESSION['userLoc'];
$userPK=$_SESSION['userPK'];
$maintainKey = $_GET['maintain'];


$db = new DatabaseConnection();
$conn = $db->createConnection();


$lastDateModified = date('Y-m-d',strtotime($_GET['endDate']));
//Making difference...1

$freqDate = $freq * 7;
$date1 = $currentdate;
$date2 = $lastDate;


$notifyDates = array();
$result = array();


/*
 * 
 * Handling Weeks 
 * nofifyDates gives all weeks
 * result gives filteres based on frequency weeks
 * 
 * */

if($units == 'Week') {
	for ($i = strtotime($date1); $i <= strtotime($date2); $i = strtotime('+1 day', $i)) {
		if (date('N', $i) == $day) {
			$notifyDates[] = date('Y-m-d', $i);
		}
	}
	
	$count = count($notifyDates) ;
	for($i=$freq;$i<$count;$i+=$freq){
		$result[] = $notifyDates[$i];
	}
	
	//print_r($result);
}


/*
 *
 * Handling Monthss
 * nofifyDates gives all months
 * result gives filteres based on frequency months
 *
 * */
if($units=='Month') {


	$today = date('Y-m');
	$modifiedDate = $today.'-'.$day;
    $start = $month = strtotime($modifiedDate);
	$end = strtotime($date2);
	while($month < $end)
	{
		$notifyDates[] = date('Y-m-d', $month);
		$month = strtotime("+1 month", $month);
	}
	
	
	$count = count($notifyDates) ;
	for($i=$freq;$i<$count;$i+=$freq){
		$result[] = $notifyDates[$i];
	}
	
	//print_r($result);
	
}

/*
 *
 * Handling Years
 * nofifyDates gives all Years
 * result gives filteres based on frequency Years
 *
 * */
if($units=='Year') {
	$start = $month = strtotime($date1);
	$end = strtotime($date2);
	while($month < $end)
	{
		$notifyDates[] = date('Y-m-d', $month);
		$month = strtotime("+1 year", $month);
	}
	
	$count = count($notifyDates) ;
	for($i=$freq;$i<$count;$i+=$freq){
		$result[] = $notifyDates[$i];
	}
	
}


/*
 * Query if data already exista in maintainance cycle
 * if exists update
 * else
 * insert
 * */
 $query;
 if($maintainKey=='NO') {
 	
 	$query = "select * from maintenancecycle where ArtefactTypeCode='$artefactType' and ArtefactCode='$artefactCode'";
 } else {
 	
 	$query = "select * from maintenancecycle where ArtefactTypeCode='$artefactType' and ArtefactCode='$artefactCode' and MaintenanceCyclePK='$maintainKey'";
 }

	
$res = $db->setQuery($query);
$rowCount = $res->num_rows;


if($rowCount > 0) {
	
	//update
	$deleteSMT = "delete from scheduledmaintenance where ArtefactCode='$artefactCode' and MaintenanceCycleFK='$maintainKey'";	
	$deleteMT = "delete from maintenancecycle where ArtefactCode='$artefactCode' and MaintenanceCyclePK='$maintainKey'";
	
	if($db->setQuery($deleteSMT)) {
		
		if($db->setQuery($deleteMT)) {
	
			$q;
			$message='';
				
			$res1;
			$q = "INSERT INTO maintenancecycle
						VALUES
						(NULL,
						'$location',
						'$artefactType',
						'$artefactCode',
						'$currentdate',
						'$freq',
						'$units',
						'$nextDate',
						'$lastDateModified',
						'$userPK',
						'$currentdate',
						'$userPK',
						'$currentdate'
						)";
			
			$maintainResult = $db->setQuery($q);
			$latRecordId = $conn->insert_id;
			
			for($i=0;$i<count($result);$i++) {
			
				$sql = "INSERT INTO scheduledmaintenance
								(	ScheduleMaintenancePK,
								MaintenanceCycleFK,
								ArtefactTypeCode,
									ArtefactCode,
									LocationFK,
									ScheduledServiceDate,
									CurrentStatus,
									CreatedDate,
									CreatedBy,
									ModifiedDate,
									ModifiedBy
									) VALUES
									(	NULL,
									'$latRecordId',
									'$artefactType',
									'$artefactCode',
									'$location',
									'$result[$i]',
									'Pending',
									CURRENT_TIMESTAMP,
									'$userPK',
									'$currentdate',
									'$userPK'
									)";
						
					$res1 = $db->setQuery($sql);
			
			}
			
			
			
			if($maintainResult) {
					$message.="Succesfully Updated";
			} else {
					$message.=$q;
			}
			
					echo $message;
		}	
	}		
} else {
	
	//insert
	$q;
	$message='';
			
		$res1;
		$q = "INSERT INTO maintenancecycle 
					VALUES
					(NULL, 
						'$location', 
						'$artefactType', 
						'$artefactCode', 
						'$currentdate', 
						'$freq', 
						'$units', 
						'$nextDate', 
						'$lastDateModified',
						 '$userPK', 
						 '$currentdate', 
						 '$userPK', 
						 '$currentdate'
					);
		";
		
		$maintainResult = $db->setQuery($q);
		$latRecordId = $conn->insert_id;
		
			for($i=0;$i<count($result);$i++) {
				
				$sql = "INSERT INTO scheduledmaintenance
							(	ScheduleMaintenancePK,
								MaintenanceCycleFK,
								ArtefactTypeCode,
								ArtefactCode,
								LocationFK,
								ScheduledServiceDate,
								CurrentStatus,
								CreatedDate,
								CreatedBy,
								ModifiedDate,
								ModifiedBy
								) VALUES
									(	NULL, 
										'$latRecordId', 
										'$artefactType', 
										'$artefactCode', 
										'$location', 
										'$result[$i]', 
										'done', 
										CURRENT_TIMESTAMP, 
										'$userPK', 
										'$currentdate', 
										'$userPK'
									)";
					
			$res1 = $db->setQuery($sql);
				
		}
		
		
	
		if($maintainResult) {
			$message.="Succesfully Updated";
		} else {
			$message.=$q;
		}
	
	echo $message;
} 

