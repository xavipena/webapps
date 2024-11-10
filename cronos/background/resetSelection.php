<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

$typeOfLine = empty($clean['type']) ? FILTERING : $clean['type'];

$_SESSION['subject'] = 0;
$_SESSION['year']    = 0;
$_SESSION['month']   = 0;
$_SESSION['country'] = "";
$_SESSION['city']    = 0;
$_SESSION['event']   = 0;
$_SESSION['person']  = 0;
$_SESSION['group']   = 0;
$_SESSION['detail']  = 0;
$_SESSION['type']    = 0;

include "../includes/movSession2Selection.inc.php";

// restore session values
if ($typeOfLine == TAGGING)
{
    include "../includes/movSelection2Session.php";
}
header("location: ".$_SESSION['url']);
?>