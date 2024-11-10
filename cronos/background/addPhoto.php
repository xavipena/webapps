<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

$photo = $_POST['p'.PHOTO];
if ($photo == "0") 
{
    Header("Location: main.php");
}
$subject = $_POST['p'.SUBJECT];
$year    = $_POST['p'.YEAR];
$month   = $_POST['p'.MONTH];
$country = $_POST['p'.COUNTRY];
$city    = $_POST['p'.CITY];
$event   = $_POST['p'.EVENT];
$person  = $_POST['p'.PERSON];
$group   = $_POST['p'.GROUP];
$detail  = $_POST['p'.DETAIL];
$type    = $_POST['p'.TYPE];

$sql =  "select IDphoto from crono_photos where IDphoto = $photo";
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result))
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
            ",IDtype  = ".$type.
            " where IDphoto = ".$photo;
}
else 
{
    $sql =  "insert into crono_photos set ".
            " IDphoto   = ".$photo.
            ",IDsubject = ".$subject.
            ",year      = ".$year.
            ",month     = ".$month.
            ",country   ='".$country."'".
            ",IDcity    = ".$city.
            ",IDevent   = ".$event.
            ",IDperson  = ".$person.
            ",IDgroup   = ".$group;
            ",IDdetail  = ".$detail.
            ",IDtype    = ".$type;
}
if (mysqli_query($db, $sql)) 
{
    // return to caller page
    Header("Location: ".$_SESSION['url']);
}
echo $sql."<br>";
echo mysqli_error($db);
exit;
?>