<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

if (empty($clean['id']))
{
    Header("Location: main.php?source=".$_SESSION['source']);
}

$id = $clean['id'];
$val = $clean['val'];
$type = $clean['type'];
            
switch($id) 
{
    case SUBJECT:
        $_SESSION['subject'] = $val;
        $_sql = " IDsubject = $val";
        break;
    case YEAR:
        $_sql = " year = $val";
        $_SESSION['year'] = $val;
        break;
    case MONTH:
        $_sql = " month = $val";
        $_SESSION['month'] = $val;
    break;
    case COUNTRY:
        $_sql = " country = '$val'";
        $_SESSION['country'] = $val;
        break;
    case CITY:
        $_sql = " IDcity = $val";
        $_SESSION['city'] = $val;
        break;
    case EVENT:
        $_sql = " IDevent = $val";
        $_SESSION['event'] = $val;
        break;
    case PERSON:
        $_sql = " IDperson = $val";
        $_SESSION['person'] = $val;
        break;
    case GROUP:
        $_sql = " IDgroup = $val";
        $_SESSION['group'] = $val;
        break;
    case DETAIL:
        $_sql = " IDdetail = $val";
        $_SESSION['detail'] = $val;
        break;
    case TYPE:
        $_sql = " IDtype = $val";
        $_SESSION['type'] = $val;
        break;
}   
$sql =  "update crono_selection set $_sql where IDline = $type";

if (mysqli_query($db, $sql)) 
{
    // return to caller page
    Header("Location: ".$_SESSION['url']);
}
echo $sql."<br>";
echo mysqli_error($db);
exit;
?>