<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

// -----------------------------
// load current filter to session
// -----------------------------
$typeOfLine = FILTERING;
include "../includes/movSelection2Session.php";

// -----------------------------
// save session to tags
// -----------------------------
$typeOfLine = TAGGING;
include "../includes/movSession2Selection.inc.php";

// -----------------------------
// read call back to return
// -----------------------------
include "../includes/readCallback.inc.php";
header("location: $url");
?>
