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
?>
</head>
<body>
<?php 
    //--- params --------------------------

    $id = empty($clean['file']) ? "" : $clean['file'];

    //--- settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    // local files

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    if ($id != "") {

        try {
            
            // Open file
            $fhd = fopen("./text/diagram_$id.txt", "r");
            while ($line = fgets($fhd)) {
                
                echo($line);
            }
            fclose($fhd);
        } 
        catch (Exception $e) {
            
            echo '**No es troba el fitxer: ',  $e->getMessage(), "\n";
        }
    }
    else echo "No hi ha dades";

    include './includes/googleFooter.inc.php';
?>