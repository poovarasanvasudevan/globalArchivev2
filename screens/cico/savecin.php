<?php

/**
    This file is called via ajax to save checkin
 *  information that can be sended from cico.php
 * This Page is called via ajax

 */
session_start();
include "../common/DatabaseConnection.php";

$i = 0;
$artefactCodeArray = array();
$purposeArray = array();
$remarksArray = array();
$statusArray = array();
$artefactType = $_GET['artefactType'];
$loop = 0;


$query = $_SERVER['QUERY_STRING'];
$vars = array();
foreach (explode('&', $query) as $pair) {
    list($key, $value) = explode('=', $pair);
    $vars[] = array(urldecode($key), urldecode($value));
}

//print_r($vars);

/** @var that has all the input value $vars */
for ($i = 0; $i < sizeof($vars); $i++) {

    for ($j = 0; $j < sizeof($vars[$i]); $j++) {

        if ($vars[$i][$j] == 'artefactCode') {
            $arCode = $vars[$i][$j + 1];

            /** @var purpose for checkin $purpose */
            $purpose = $arCode . "_purpose";


            /** @var Purpose for checkout $remarks */
            $remarks = $arCode . "_remarks";

            array_push($purposeArray, $_GET[$purpose]);
            array_push($remarksArray, $_GET[$remarks]);
            array_push($artefactCodeArray, $arCode);
        }

    }
}

$checkout;
$result = '';

if (sizeof($artefactCodeArray) > 0) {


    for ($i = 0; $i < sizeof($artefactCodeArray); $i++)
    {

        $checkout = new CheckInOut($artefactCodeArray[$i], $purposeArray[$i], $remarksArray[$i], $artefactType);
        $checkout->presist();
        //echo $artefactCodeArray[$i]."-->".$purposeArray[$i]."-->".$statusArray[$i]."-->".$remarksArray[$i];

        /*
         * Checkin data Saved Succesfully
         * */
        $result = "done";
    }
} else {
    $result = "undone";
}

echo $result;

class CheckInOut
{

    var $code;
    var $purpose;
    var $status;
    var $remarks;
    var $loc;
    var $user;
    var $db;
    var $type;

    /*
     * constructing an object array for persist
     * Information from the form
     *
     * */
    function __construct($code, $purpose, $remarks, $type)
    {
        $this->code = $code;
        $this->remarks = $remarks;
        $this->status = "close";
        $this->purpose = $purpose;
        $this->type = $type;
        $this->loc = $_SESSION['userLoc'];
        $this->user = $_SESSION['userPK'];
        $this->db = new DatabaseConnection();

    }

    /**
    Store information into the database
     *

     */
    function presist()
    {
        $this->db->createConnection();
        $q = "UPDATE artefactcico
					SET
					`CICOTYPE` = 'checkin',
					`CICOStatus` = '$this->status',
					`CheckInDate` = CURRENT_TIMESTAMP,
					`Purpose` = '$this->purpose',
					`Remarks` = '$this->remarks',
					`ModifiedBy` ='$this->user',
					`ModifiedDate` = CURRENT_TIMESTAMP
					 WHERE `cicosk` = '$this->code'
					 and ArtefactTypeCode='$this->type';				
				";
        $this->db->setQuery($q);
        //echo $q;
    }
}