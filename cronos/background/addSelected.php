<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

$photo = empty($clean['photo']) ? "" : $clean['photo'];
if (empty($photo)) exit;

$sql = "select count(*) from cronos_sel_work where IDphoto = $photo";
$result = mysqli_query($pw, $sql);
$cnt = mysqli_fetch_array($result)[0];
if ($cnt == 0) 
{
    $sql =  "insert into crono_sel_work set ".
            " IDphoto   = ".$row['image_id'].
            ",IDsubject = 0".
            ",year      = 0".
            ",month     = 0".
            ",country   =''".
            ",IDcity    = 0".
            ",IDevent   = 0".
            ",IDperson  = 0".
            ",IDgroup   = 0".
            ",IDdetail  = 0".
            ",IDtype    = 0";

    mysqli_query($db, $sql);
}
?>