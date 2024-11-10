<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

$type = $clean['mType'];
$name = $clean['mText'];

switch($type)
{
    case SUBJECT:
        $title  = "Manteniment de Temes";
        $tableName = "crono_subjects";
        $fieldID = "IDsubject";
        break;
    case CITY:
        $title  = "Manteniment de Ciutats";
        $tableName = "crono_cities";
        $fieldID = "IDcity";
        break;
    case EVENT:
        $title  = "Manteniment d'Events";
        $tableName = "crono_events";
        $fieldID = "IDevent";
        break;
    case PERSON:
        $title  = "Manteniment de Persones";
        $tableName = "crono_persons";
        $fieldID = "IDperson";
        break;
    case GROUP:
        $title  = "Manteniment de Grups";
        $tableName = "crono_groups";
        $fieldID = "IDgroup";
        break;
    case DETAIL:
        $title  = "Manteniment de Detalls";
        $tableName = "crono_details";
        $fieldID = "IDdetail";
        break;
    case TYPE:
        $title  = "Manteniment de Tipus de foto";
        $tableName = "crono_types";
        $fieldID = "IDtype";
        break;
}
// ----------------------------
// Check if the value exists
// ----------------------------
$sql =  "select $fieldID from $tableName";
$result = mysqli_query($db, $sql);
if ($id = mysqli_fetch_array($result))
{
    // Found
    Header("Location: ../metadata.php?id=".$type);
}

// ----------------------------
// Insert
// ----------------------------
$sql =  "select $fieldID from $tableName order by $fieldID desc limit 1";
$result = mysqli_query($db, $sql);
$id = mysqli_fetch_array($result)[0];
$id += 1;

$sql =  "insert into $tableName set ".
        " $fieldID  = ".$id.
        ",name      ='".$name."'";

if ($type == CITY)
{
    $sql .= ",country = '".$_SESSION['country']."'";
}

if (mysqli_query($db, $sql)) 
{
    Header("Location: ../metadata.php?id=".$type);
}
echo $sql."<br>";
echo mysqli_error($db);
?>
