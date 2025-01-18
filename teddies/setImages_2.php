<?php
session_start();

include "./includes/dbConnect.inc.php";    // $db4
include "./includes/dbConnect.inc.php";    // $db
include './includes/googleSecurity.inc.php';

//--- Settings -----------------------

$database = $db4;
$path = "./images/";
$tedID = $clean['tedID'];
$tedIM = $clean['tedIMG'];

//--- new content -------------------- 

$newName = "ted_".$tedID.".jpg";

if (file_exists($path.$tedIM)) {

    if (!file_exists($path.$newName)) {

        rename($path.$tedIM, $path.$newName);
    }
}

// --- end content -------------------

header("location: main.php");
?>