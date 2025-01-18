<?php
session_start();
$page = "teddies";
include "./includes/dbConnect.inc.php";    // $db4
include "./includes/dbConnect.inc.php";    // $db
include './includes/googleSecurity.inc.php';

$tedID = $clean['tedID'];

// --------------------------------
// find id
// --------------------------------

$sql = "select * from ted_teddies where IDted = $tedID";
$result = mysqli_query($db4, $sql);
if ($row = mysqli_fetch_array($result)) {

        $sql =  "update ted_teddies set ".
                " name          ='".$clean['tedNam']."'".
                ",adquiredDate  ='".$clean['tedDat']."'".
                ",price         = ".$clean['tedPri'].
                //      ",rank          = 0".
                ",IDsize        = ".$clean['tedSiz'].
                ",IDtype        = ".$clean['tedTyp'].
                ",status        ='".$clean['tedSta']."'".
                ",origin        ='".$clean['tedOrg']." '".
                ",remarks       ='".$clean['tedRem']." '".
                ",vendor        = ".$clean['tedVen'].
                " where IDted = ".$tedID;
        mysqli_query($db4, $sql);
        header("location: catalog.php");
        exit();
}
else {

        // --------------------------------
        // get last number
        // --------------------------------

        $sql = "select IDted from ted_teddies order by IDted desc limit 1";
        $result = mysqli_query($db4, $sql);
        $row = mysqli_fetch_assoc($result);
        $tedID = $row['IDted'] + 1;

        // --------------------------------
        // insert into table
        // --------------------------------

        $sql =  "insert into ted_teddies set ".
                " IDted         = ".$tedID.
                ",name          ='".$clean['tedNam']."'".
                ",adquiredDate  ='".$clean['tedDat']."'".
                ",price         = ".$clean['tedPri'].
                //      ",rank          = 0".
                ",IDsize        = ".$clean['tedSiz'].
                ",IDtype        = ".$clean['tedTyp'].
                ",status        ='".$clean['tedSta']."'".
                ",origin        ='".$clean['tedOrg']." '".
                ",remarks       ='".$clean['tedRem']." '".
                ",vendor        = ".$clean['tedVen'].
                ",link          = ''";

        mysqli_query($db4, $sql);
        header("location: setImages.php");
}
