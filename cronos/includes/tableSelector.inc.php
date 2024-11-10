<?php
// -----------------------------
// Require $type
// Return:
//      $title
//      $tableName
//      $fieldID
// -----------------------------
switch ($type)
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
?>