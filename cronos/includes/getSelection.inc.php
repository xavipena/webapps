<?php
// -----------------------------
// Retrive selection from photo to session variables
// Required: $photo
// -----------------------------
$sql =  "select * from crono_photos where IDphoto = $photo";
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {

    $_SESSION['subject'] = $row['IDsubject'];
    $_SESSION['year']    = $row['year'];
    $_SESSION['month']   = $row['month'];
    $_SESSION['country'] = $row['country'];
    $_SESSION['city']    = $row['IDcity'];
    $_SESSION['event']   = $row['IDevent'];
    $_SESSION['person']  = $row['IDperson'];
    $_SESSION['group']   = $row['IDgroup'];
    $_SESSION['detail']  = $row['IDdetail'];
    $_SESSION['type']    = $row['IDtype'];
}
?>