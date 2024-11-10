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

    // 68,70,71,72,73,74,75,76,77,83,84,85,88,89,90,92,93,95,97,98,99,100
?>
<style>
    .buttons {
        position: relative;
        /* Arriba | Derecha | Abajo | Izquierda */
        margin: 20px 0px 20px 0px;
    }
</style>
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    $data = empty($clean['data']) ? 0 : $clean['data'];
    
    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    include "./includes/lists.inc.php";
    
    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    //------------------------------------
    echo "<table cellpadding='10'><tr><td width='30%'>";
    //------------------------------------

    ProductStock($db);

    //------------------------------------
    echo "</td><td width='30%'>";
    //------------------------------------

    ShoppingList($db, 0);

    //------------------------------------
    echo "</td></tr></table>";
    //------------------------------------

    include './includes/googleFooter.inc.php';
?>