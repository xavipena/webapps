<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
    include "./includes/background.inc.php";

    $week = empty($clean['week']) ? 1 : $clean['week'];
?>
    <script type="text/javascript" src="../js/chart.min.js"></script>
    <style>
        iframe {
            border: none;
        }
    </style>
</head>
<body>
<?php 

    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 


    //--- new content -------------------- 

    include "./includes/menu.inc.php"; 
    include "./includes/settingsEnd.inc.php";
?>
    Evolució del pes
    <iframe src="xDietTraceWeight.php"
        height="350px" width="100%">
    </iframe>
    Increments sobre calories enregistrades
    <iframe src="xDietTraceWDelta.php"
        height="350px" width="100%">
    </iframe>
    En el periode
    <iframe src="xDietTraceWPeriod.php"
        height="350px" width="100%">
    </iframe>
<?php

    include './includes/googleFooter.inc.php';
?>