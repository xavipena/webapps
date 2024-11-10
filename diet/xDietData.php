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
    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $sourceTable = "diet_conversions, diet_limits";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    $menuType = MENU_DIET;
    include "./includes/dietMenu.inc.php";

echo "<table width='400px' cellpadding='5'>";

echo "<tr><td colspan='9'><h1>Conversió</h1></td></tr>";
$sql ="select * from diet_conversion";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) {

    echo "<tr><td>".$row['name']."</td><td>".$row['cal']." ".$row['conversion']."</td></tr>";
}

echo "</table>";
echo "<table width='400px' cellpadding='5'>";

echo "<tr><td colspan='9'><h1>Limits recomanats</h1></td></tr>";
$c =0;
$sql ="select * from diet_limits";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    echo "<tr><td>".$row['name']."</td><td>entre ".$row['min']."</td><td>i ".$row['max'].$row['unit']."</td><td>millor entre ".$row['bestmin']."</td><td>i ".$row['bestmax'].$row['unit']."</td></tr>";
    $c += 1;
}
echo "</table>";

// Conversor
$color = "#cccccc";
?>
<div style="position:absolute;width:300px;top:300px;left:600px"><iframe src="https://convertlive.com/es/w/convertir/calor%C3%ADas/a/julios" frameBorder="0" width="300px" height="280px" style="border:medium none;overflow-x:hidden;overflow-y:hidden;margin-bottom:-5px;"><p>Su navegador no soporta iframes. <a href="https://convertlive.com/es/convertir">convertlive</a>.</p></iframe><a target="_blank" rel="noopener" style="position:absolute;bottom:7px;right:15px;font-family:monospace;color:<?php echo $color ?>;font-size:12px;font-weight:700;" href="https://convertlive.com/es/convertir">convertlive</a></div>
<?php
include './includes/googleFooter.inc.php';
?>