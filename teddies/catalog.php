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
//--- Params -------------------------

$type = empty($clean['tdTyp']) ? 0 : $clean['tdTyp'];

//--- Settings -----------------------

$_SESSION['pageID'] = PAGE_TEDDIES;
$sourceTable = "ted_teddies";
$database = $db4;
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";

//--- functions --------------------- 

include "./includes/functions.inc.php";

function showCard($row) {

    $class = "cardImageRound";
    $image = "./images/ted_".$row['IDted'].".jpg";
    if (!file_exists($image)) {
    
        $image = "./images/no_image.png";
        $class .= " cardImageTrans";
    } 

    $sql = "select tname from ted_types where IDtype = ".$row['IDtype'];
    $result = mysqli_query($GLOBALS['database'], $sql);
    $type = mysqli_fetch_array($result)[0]; 

    echo "<div class='card'>";
    echo "   <div class='round_transparent'>";
    echo "      <div style='height:110px; padding:5px;'>";
    echo "          <table cellpadding='5px'><tr><td>";
    echo "              <a href='details.php?id=".$row['IDted']."'>";
    echo "                  <img class='$class' src='$image'>";
    echo "              </a>";
    echo "          </td><td>";
    echo "              ".$row['name']."<br><br><span class='smallText'>".$row['remarks']."<br>Tipus: $type</span>";
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

echo "<form method='post' action'catalog.php'>";
echo "<label for='tdTyp'>Per tipus d'animal:&nbsp;</label>";
echo "<select name='tdTyp'>";
echo "<option value='0'>Tots</option>";
$sql = "select * from ted_types order by tname";
$result = mysqli_query($database, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $selected = $row['IDtype'] == $type ? "selected" : "";
    echo "<option value='".$row['IDtype']."' $selected>".$row['tname']."</option>";
}
echo "</select>";
echo "&nbsp;<input type='submit' value='Filtra'>";
echo "</form>";
echo "</div>";

echo "<hr>";

echo "<div class='container'>";

$sql = "select * from ted_teddies where status = 'A'";
if ($type > 0) $sql .= " and IDtype = $type";
$sql .= " order by name";
$result = mysqli_query($database, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    ShowCard($row);
}
echo "</div>";

// --- end content -------------------

include './includes/googleFooter.inc.php';
?>