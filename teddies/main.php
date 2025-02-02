<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "teddies";
    
    include "./includes/dbConnect.inc.php";    // $db4
    include "./includes/dbConnect.inc.php";    // $db
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
</head>
<body>
<?php 
//--- Settings -----------------------

$_SESSION['pageID'] = PAGE_TEDDIES;
$sourceTable = "ted_teddies";
$database = $db4;
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";

//--- functions --------------------- 

include "./includes/functions.inc.php";

//--- new content -------------------- 

include "./includes/menu.inc.php"; 
include "./includes/settingsEnd.inc.php";

$c = 0;
echo "<div class='container'>";

ShowActionCard("Catàleg", "catalog.php", "Catàleg de fitxes dels teddies");
ShowActionCard("Nou", "addNew.php", "Fer la fitxa de un nou teddie");
ShowActionCard("Enllaços", "links.php", "Enllaços a recursos d'Internet sobre peluixos");
ShowActionCard("DIP", "dips.php", "Tots els DIPs (Document de Identificació de Peluix)");

echo "</div>";
echo "<div class='container'>";

if (countOrfan() > 0) {
    
    ShowActionCard("Imatges", "setImages.php", "Imatges sense assignar a un teddie");
}
ShowActionCard("Tots", "allImages.php", "Totes les imatges");

echo "</div>";

// --- end content -------------------

include './includes/googleFooter.inc.php';
?>