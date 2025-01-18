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

function showActionCard($name, $page, $text) {

    $image = "./images/edit_logo.jpg";

    echo "<div class='card'>";
    echo "   <div class='round_transparent'>";
    echo "      <div style='height:110px; padding:5px;'>";
    echo "          <table cellpadding='5px'><tr><td>";
    echo "              <a href='$page'>";
    echo "                  <img class='cardImageRound' src='$image'>";
    echo "              </a>";
    echo "          </td><td>";
    echo "              $name<br><br><span class='smallText'>$text</span>";
    echo "          </td><tr></table>";
    echo "      </div>";
    echo "   </div>";
    echo "</div>";
}

//--- new content -------------------- 

include "./includes/menu.inc.php"; 
include "./includes/settingsEnd.inc.php";

$c = 0;
echo "<div class='container'>";

if (countOrfan() > 0) {
    
    ShowActionCard("Imatges", "setImages.php", "Imatges sense assignar a un teddie");
}
ShowActionCard("Tots", "allImages.php", "Totes les imatges");
ShowActionCard("Nou", "addNew.php", "Fer la fitxa de un nou teddie");
ShowActionCard("Catàleg", "catalog.php", "Catàleg de fitxes dels teddies");

echo "</div>";

// --- end content -------------------

include './includes/googleFooter.inc.php';
?>