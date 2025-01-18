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



//--- new content -------------------- 

include "./includes/menu.inc.php"; 
include "./includes/settingsEnd.inc.php";

$c = 0;
echo "<div class='container'>";

$sql = "select * from ted_slides order by name";
$result = mysqli_query($database, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $class = "cardImageRound";
    $image = "./images/no_image.png";

    echo "<div class='card'>";
    echo "   <div class='round_transparent'>";
    echo "      <div style='height:110px; padding:5px;'>";
    echo "          <table cellpadding='5px'><tr><td>";
    echo "              <a href='slides.php?id=".$row['IDslide']."'>";
    echo "                  <img class='$class' src='$image'>";
    echo "              </a>";
    echo "          </td><td>";
    echo "              ".$row['name']."<br><br><span class='smallText'>".$row['story']."</span>";
    echo "          </td><tr></table>";
    echo "      </div>";
    echo "   </div>";
    echo "</div>";
}
echo "</div>";

// --- end content -------------------

include './includes/googleFooter.inc.php';
?>