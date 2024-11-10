<?php
include "../includes/dbConnect.inc.php";
include "../includes/pwConnect.inc.php";
include "../includes/config.inc.php";

$cat = empty($clean['cat']) ? "0" : $clean['cat'];

$sql =  "select * from crono_selection where IDline = ".TAGGING;
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);

$subject = $row['IDsubject'];
$year    = $row['year'];
$month   = $row['month'];
$country = $row['country'];
$city    = $row['IDcity'];
$event   = $row['IDevent'];
$person  = $row['IDperson'];
$group   = $row['IDgroup'];
$detail  = $row['IDdetail'];
$type    = $row['IDtype'];

$upd = 0;
$ins = 0;

$sql = "select * from piwigo_image_category where category_id = ".$cat;
$result = mysqli_query($pw, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    //$c += 1;
    $sql = "select ".$row['image_id']." from crono_photos where IDphoto = ".$row['image_id'];
    $res = mysqli_query($db, $sql);
    if ($w = mysqli_fetch_array($res))
    {
        $sql =  "update crono_photos set ".
                " IDsubject = ".$subject.
                ",year      = ".$year.
                ",month     = ".$month.
                ",country   ='".$country."'".
                ",IDcity    = ".$city.
                ",IDevent   = ".$event.
                ",IDperson  = ".$person.
                ",IDgroup   = ".$group.
                ",IDdetail  = ".$detail.
                ",IDtype    = ".$type.
                " where IDphoto   = ".$row['image_id'];
        $upd += 1;
    } 
    else
    {
        $sql =  "insert into crono_photos set ".
                " IDphoto   = ".$row['image_id'].
                ",IDsubject = ".$subject.
                ",year      = ".$year.
                ",month     = ".$month.
                ",country   ='".$country."'".
                ",IDcity    = ".$city.
                ",IDevent   = ".$event.
                ",IDperson  = ".$person.
                ",IDgroup   = ".$group.
                ",IDdetail  = ".$detail.
                ",IDtype    = ".$type;
        $ins += 1;
    }
    if (mysqli_query($db, $sql))
    {
        // -----------------------------
        // trace catalog as imported
        // -----------------------------
        $sql = "select * from crono_pw_albums where IDcat = $cat";
        $res = mysqli_query($db, $sql);
        if (!$row = mysqli_fetch_array($res))
        {
            $sql = "insert into crono_pw_albums set IDcat = $cat";
            mysqli_query($db, $sql);
        }
    }
}

// -----------------------------
// get callback
// -----------------------------
$url = "";
include "./includes/readCallback.inc.php";

header("location: ../stats.php?upd=$upd&ins=$ins&url=$url");
?>