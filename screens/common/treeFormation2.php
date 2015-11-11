<?php
/**
 * Created by PhpStorm.
 * User: Poovarasan Vasudevan
 * Date: 17/10/2015
 * Time: 07:00
 */

require_once('DatabaseConnection.php');

$type = $_GET['type'];

$db = new DatabaseConnection();
$db->createConnection();

$artefacts = array();

$sql = "";


