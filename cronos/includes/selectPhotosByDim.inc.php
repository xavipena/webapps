<?php
// -----------------------------
// Local functions
// -----------------------------
function UpdateSelectionFor($dimension)
{
    switch ($dimension)
    {
        case "time":

            $_SESSION['country'] = "";
            $_SESSION['city'] = 0;
            $_SESSION['event'] = 0;

            $_SESSION['person'] = 0;
            $_SESSION['group'] = 0;
            break;
    
        case "place":

            $_SESSION['year'] = 0;
            $_SESSION['month'] = 0;

            $_SESSION['person'] = 0;
            $_SESSION['group'] = 0;
            break;
    
        case "people":
            
            $_SESSION['country'] = "";
            $_SESSION['city'] = 0;
            $_SESSION['event'] = 0;

            $_SESSION['year'] = 0;
            $_SESSION['month'] = 0;
            break;
    }
}

// -----------------------------
// Mount select to retrieve photos
// -----------------------------
$where = "";

$sql = "select * from crono_selection where IDline = ".FILTERING;
$result = mysqli_query($db, $sql);
$selection = mysqli_fetch_array($result);

// -----------------------------
// Common
// -----------------------------
if ($_SESSION['subject'] > 0) $where .= " and cp.IDsubject = ".$_SESSION['subject'];
if ($_SESSION['detail'] > 0) $where .= " and cp.IDdetail = ".$_SESSION['detail'];
if ($_SESSION['type'] > 0) $where .= " and cp.IDtype = ".$_SESSION['type'];

// -----------------------------
// Select by dimmensions
// -----------------------------
switch ($dimension)
{
    case "time":

        if ($_SESSION['year'] > 0) $where .= " and cp.year = ".$_SESSION['year'];
        if ($_SESSION['month'] > 0) $where .= " and cp.month = ".$_SESSION['month'];
        $sortedby = " order by cp.year, cp.month";
        break;

    case "place":

        if ($_SESSION['country'] != '') $where .= " and cp.country = '".$_SESSION['country']."'";
        if ($_SESSION['city'] > 0) $where .= " and cp.IDcity = ".$_SESSION['city'];
        if ($_SESSION['event'] > 0) $where .= " and cp.IDevent = ".$_SESSION['event'];
        $sortedby = " order by cp.country, cp.IDcity, cp.IDevent";
        break;

    case "people":

        if ($_SESSION['group'] > 0) 
        {
            // IDperson or IDgroup || only IDgroup
            if ($_SESSION['person'] > 0) $where .= " and (cp.IDperson = ".$_SESSION['person']." or "; 
            else $where .= " and ";
            $where .=   "cp.IDperson in ( ".
                        "     select IDperson from crono_group_ppl where IDgroup = ".$_SESSION['group'].
                        " )";
            if ($_SESSION['person'] > 0) $where .= ")";
        } 
        else
        {
            // no group selected
            if ($_SESSION['person'] > 0) $where .= " and cp.IDperson = ".$_SESSION['person'];
        }
        $sortedby = " order by cp.IDgroup, cp.IDperson";
        break;
}
$sql = "select * from crono_photos cp where cp.IDphoto > 0 $where $sortedby";

// -----------------------------
// Clear other dimensions
// -----------------------------
UpdateSelectionFor($dimension);

// -----------------------------
// Save session values to database
// -----------------------------
$typeOfLine = FILTERING;
include __DIR__."/movSession2Selection.inc.php";
?>