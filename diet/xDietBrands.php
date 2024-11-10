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
    include "./includes/dietFunctions.inc.php";
?>
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $sourceTable = "diet_product_brands";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 


    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    include "./includes/dietMenu.inc.php";

echo "<a href='xDietBrands_1.php'>Nova</a><br><br>";
echo "<table width='80%'>";
$c = 1;
$rowColor = "odd";
$sql =  "select * from diet_product_brands where IDbrand > 0 ".
        "order by name";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    $rowColor = $c % 2 == 0 ?  "odd" : "even";
    echo "<tr class='".$rowColor."'><td rowspan='2'><img class='brand' src='".getImage("brand", $row['IDbrand'])."'></td>";
    echo "  <td><div class='round'>".$row['name']."</div></td>";
    echo "  <td><div class='round'>".$row['company']."</div></td></tr>";
    echo "<tr class='".$rowColor."'><td colspan='2' valign='top'>".$row['description']."</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    $c += 1;
}
echo "</table>";
echo $c." marques";
echo "</div>"; 
?>
</body>
</html>