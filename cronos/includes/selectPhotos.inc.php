<?php
// -----------------------------
// Mount select to retrieve photos depending on the filters
// -----------------------------
$where = "";
$sortedby = "";

// -----------------------------
// Paginator
// -----------------------------
$page = $page == 0 ? 1 : $page;
$items_per_page = PER_PAGE;
$offset = ($page - 1) * $items_per_page;

if ($_SESSION['subject'] > 0) $where .= " and IDsubject = ".$_SESSION['subject'];
if ($_SESSION['year'] > 0) $where .= " and year = ".$_SESSION['year'];
if ($_SESSION['month'] > 0) $where .= " and month = ".$_SESSION['month'];
if ($_SESSION['country'] > 0) $where .= " and country = '".$_SESSION['country']."'";
if ($_SESSION['city'] > 0) $where .= " and IDcity = ".$_SESSION['city'];
if ($_SESSION['event'] > 0) $where .= " and IDevent = ".$_SESSION['event'];
if ($_SESSION['group'] > 0) 
{
    // IDperson or IDgroup || only IDgroup
    if ($_SESSION['person'] > 0) $where .= " and (IDperson = ".$_SESSION['person']." or "; else $where .= " and ";
    $where .= "IDperson in ( ".
    "     select IDperson from crono_group_ppl where IDgroup = ".$_SESSION['group'].
    " )";
    if ($_SESSION['person'] > 0) $where .= ")";
} 
else
{
    // no group selected
    if ($_SESSION['person'] > 0) $where .= " and IDperson = ".$_SESSION['person'];
}
if ($_SESSION['detail'] > 0) $where .= " and IDdetail = ".$_SESSION['detail'];
if ($_SESSION['type'] > 0) $where .= " and IDtype = ".$_SESSION['type'];

// -----------------------------
// Paginator
// -----------------------------
$sql = "select count(*) from crono_photos where IDphoto > 0 $where";
$result = mysqli_query($db, $sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows / $items_per_page);

// -----------------------------
// Get the count
// -----------------------------
$cnt = $total_rows;

// -----------------------------
// Count all
// -----------------------------
$sql = "select count(*) as cnt from crono_photos where IDphoto > 0";
$result = mysqli_query($db, $sql);
$tot = mysqli_fetch_array($result)[0]; 

// -----------------------------
// sql to return
// -----------------------------
$sql = "select * from crono_photos where IDphoto > 0 $where limit $offset, $items_per_page";
?>