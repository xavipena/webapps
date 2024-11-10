<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

// -----------------------------
// clear current selection
// -----------------------------
$sql =  "delete from crono_sel_work";
mysqli_query($db, $sql);

// -----------------------------
// current selection
// -----------------------------
include "../includes/selectPhotos.inc.php";

// -----------------------------
// override sql, keep where, no paging
// -----------------------------
$sql = "select * from crono_photos where IDphoto > 0 $where";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $image = $row['IDphoto'];

    $sql =  "insert into crono_sel_work set ".
            " IDphoto   = ".$row['IDphoto'].
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

    mysqli_query($db, $sql);}

// -----------------------------
// read call back to return
// -----------------------------
include "../includes/readCallback.inc.php";
header("location: $url");
?>