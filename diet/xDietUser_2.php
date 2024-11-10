<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$isWebApp = $_POST['dtWebApp'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$_SESSION['diet_basal'] = 0;
$_SESSION['diet_recom'] = 0;
$_SESSION['diet_limit'] = 0;
$_SESSION['diet_loss'] = 0;
$_SESSION['diet_target'] = 'B';

if ($clean['dtUser'] != "") {

    $_SESSION['diet_user'] = $clean['dtUser'];

    $sql ="select * from diet_users where IDuser = ".$clean['dtUser'];
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $_SESSION['diet_target'] = $row['target'];
        $_SESSION['diet_basal']  = $row['basal'];
        $_SESSION['diet_recom']  = $row['recommended'];
        $_SESSION['diet_limit']  = $row['limited'];
        $_SESSION['diet_loss']   = $row['lossDiet'];
    }
}
else unset($_SESSION['diet_user']);

switch ($clean['dtSource']) {
    case 1:
        Header("Location: xDietUser_1.php");
        break;
    case 2:
        Header("Location: apps/weight.php");
        break;
}
?>
