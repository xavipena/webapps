<?php
// ------------------------------------------
// Part from googleHeader.inc.php
// To allow css hierarchy override side menu styles
// ------------------------------------------

    echo "<link rel='stylesheet' type='text/css' href='./css/google.css' />";
    echo "<link rel='stylesheet' type='text/css' href='./css/card.css' />";
    echo "<link rel='stylesheet' type='text/css' href='./css/diet.css' />";
    echo "<link rel='stylesheet' type='text/css' href='./css/dietdiet.css' />";
    if ($cookiesInUse == YES) { 
?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@3.0.1/dist/cookieconsent.css">
<?php
    }
    echo "<script src='./js/jquery-3.3.1.min.js'></script>";
    echo "<script src='./js/google.js'></script>";
    echo "<script src='./js/diet.js'></script>";
?>