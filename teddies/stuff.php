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

function PCard($name, $text, $url, $img) {

    $class = "cardImageRound";
    $image = empty($img) ? "no_image.png" : $img;
    $image = "./images/".$image;
    
    echo "<div class='card'>";
    echo "   <div class='round_transparent'>";
    echo "      <div style='height:110px; padding:5px;'>";
    echo "          <table cellpadding='5px'><tr><td>";
    echo "              <a href='$url'>";
    echo "                  <img class='$class' src='$image'>";
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

PCard("Panda", "Panda animat de dia i de nit", "./panda/panda_1.php", "");
PCard("Panda caminant", "Panda animat que passa caminant", "./panda/panda_2.php", "");
PCard("Aniversari", "La postal dels teddies de la casa", "./birthday", "panda-b.jpg");
PCard("Codi secret", "Entra per a trobar el codi secret de la felicitació", "./impossible", "panda-b.jpg");

echo "</div>";

// --- end content -------------------

include './includes/googleFooter.inc.php';
?>