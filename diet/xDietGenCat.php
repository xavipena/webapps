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

    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    // local files

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    //--- new content -------------------- 

    // Referència a:
    // https://canalsalut.gencat.cat/ca/vida-saludable/alimentacio/petits-canvis-menjar-millor/

    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    echo "<div class='container'>";
        AddTestCard("xDietGenCat_1.php?grp=1","diet","Més","Aliments que és important consumir en més quantitat i amb més freqüència", 4);
        AddTestCard("xDietGenCat_1.php?grp=2","diet","Canvia","Aliments dels quals convé canviar-ne la qualitat o el tipus per altres versions més saludables", 4);
        AddTestCard("xDietGenCat_1.php?grp=3","diet","Menys","Aliments el consum dels quals cal reduir perquè es relaciona amb importants problemes de salut", 4);
    echo "</div>";

    include './includes/googleFooter.inc.php';
?>