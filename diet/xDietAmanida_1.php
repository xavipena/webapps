<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
if (empty($_SESSION['diet_user'])) Header("Location: xDietAmanides.php");

include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

//--- Params ----------------------- 

$ID = $clean['prd'];

//--- Query ------------------------ 

// if already exists, ignore
$sql = "insert ignore into diet_user_salad set".
        " IDuser      =".$_SESSION['diet_user'].
        ",IDproduct   = ".$ID.
        ",quantity    = 1";

mysqli_query($db, $sql);
Header("Location: xDietAmanides.php");
?>
