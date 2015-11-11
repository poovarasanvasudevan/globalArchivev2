<?php
/**
 * Created by PhpStorm.
 * User: Poovarasan
 * Date: 11-11-2015
 * Time: 09:59
 */
session_start();
include '../common/DatabaseConnection.php';
$ds = DIRECTORY_SEPARATOR;  //1


$db = new DatabaseConnection();
$db->createConnection();

$storeFolder = '..\..\uploads\\' . $_POST['artefacttype'] . "\\";


if (isset($_FILES) && isset($_POST['artefactcode']) && isset($_POST['artefacttype']) && isset($_POST['attributecode'])) {
    $ret = array();


    $error = $_FILES["files"]["error"];
    //You need to handle  both cases
    //If Any browser does not support serializing of multiple files using FormData()
    if (!is_array($_FILES["files"]["name"])) //single file
    {

        $fileName = $_FILES["files"]["name"];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        move_uploaded_file($_FILES["files"]["tmp_name"], $storeFolder . $fileName);
        $db->updateuploadeddata($_POST['artefactcode'], $fileName, $_POST['artefacttype'], $_POST['attributecode'], $_SESSION['userPK'], $ext);
        //$ret[] = $fileName;
    } else {
        //echo $storeFolder;
        $fileCount = count($_FILES["files"]["name"]);
        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = $_FILES["files"]["name"][$i];
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["files"]["tmp_name"][$i], $storeFolder . $fileName);
            $db->updateuploadeddata($_POST['artefactcode'], $fileName, $_POST['artefacttype'], $_POST['attributecode'], $_SESSION['userPK'], $ext);
            //$ret[] = $fileName;
        }

    }
    echo "success";
} else {
    echo "error";
}
?>