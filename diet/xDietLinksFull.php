<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
</head>
<body>
<?php 
    $page = "diet";
    $sourceTable = "diet_links";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    include "./includes/dietMenu.inc.php";
    include "./includes/settingsEnd.inc.php";

echo "<h1>Receptes</h1>";
echo "<table width='80%' cellpadding='5'>";

$auxlink = "";
$sql = "select * from diet_links where linkType = 'R' order by name";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    echo "<tr><td><img src='../images/diet_recipe_".$row['IDlink'].".jpg' width='200xp'></td>"; 
    echo "<td><a class='smallLink' target='_blank' href='".$row['url']."'>".$row['name']."</a><br>ID: ".$row['IDlink']."</td>"; 
    echo "<td>".$row['description']."</td></tr>";
}
echo "</table>";

include './includes/googleFooter.inc.php';
?>