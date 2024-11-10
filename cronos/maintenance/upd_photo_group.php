<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

$sql = "select * from crono_photos where IDperson > 0 or IDgroup > 0";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $sql = "select IDphoto from crono_photo_ppl where IDphoto = ".$row['IDphoto'];
    $res = mysqli_query($db, $sql);
    if ($pho = mysqli_fetch_array($res)) 
    {
        $upd = "update crono_photo_ppl set IDperson = ".$pho['IDperson'].", IDgroup = ".$pho['IDgroup']." wehre IDphoto = ".$pho['IDphoto'];
        mysqli_query($db, $upd);
    }
    else
    {
        $upd =  "insert into crono_photo_ppl set ".
                " IDphoto   = ".$row['IDphoto'].
                ",IDperson  = ".$row['IDperson'].
                ",IDgroup   = ".$row['IDgroup'];
        mysqli_query($db, $upd);
    }
}
Header("Location: main.php");
?>