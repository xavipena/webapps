<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

//--- Params ----------------------- 

$ID = $clean['prd'];
$user = "0";
if (isset($_SESSION['diet_user'])) {

    if ($_SESSION['diet_user'] != "") {
        
        $user = $_SESSION['diet_user'];
    }
}

//--- Query ------------------------ 

// if already exists, ignore
$sql = "insert ignore into diet_compare set".
        " IDuser    = ".$user.
        ",IDproduct = ".$ID;

mysqli_query($db, $sql);
Header("Location: xDietCompare.php");
?>
