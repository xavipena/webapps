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
    <style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 35%), url("./images/background.webp");
            background-size: cover;
        }
    </style>
</head>
<body>
<?php 

//--- Settings ------------------------

$_SESSION['pageID'] = PAGE_DIET;
$sourceTable = "";
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";

//--- functions -------------------- 

include "./includes/dietFunctions.inc.php";
include "./includes/cards.inc.php";

//--- new content -------------------- 

//echo "<div class='dietPageContainer'>";
include "./includes/settingsEnd.inc.php";
include "./includes/menu.inc.php"; 

$sql ="select count(*) as cnt from diet_products";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    $num1 = $row['cnt'];
}
$sql ="select count(*) as cnt from diet_dishes";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    $num2 = $row['cnt'];
}
$num3 = 0;

$section = 0;
$sql = "select diet_menu.*, sName from diet_menu ".
       "join diet_menu_sections ".
       "  on diet_menu.IDsection = diet_menu_sections.IDsection and diet_menu_sections.lang = '$lang' ".
       "where diet_menu_sections.IDsection in (3, 4, 5) and diet_menu.lang = '$lang'".
       "order by diet_menu_sections.IDsection, sequence";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    if ($section != $row['IDsection'])
    {
        if ($section > 0)
        {
            echo "</div>";
        }
        echo "<br><div class='round'>".$row['sName']."</div>";        
        echo "<div class='container'>";
        $section = $row['IDsection'];
    }
    AddCard($row['page'],"diet",$row['name'],$row['description']);
}
echo "</div>";

// ------------- new uprised from testing ------------------

echo "<br><div class='round'>Cites i dites</div>";        
echo "<div class='container'>";
AddCard("xDietQuotes.php?type=Q","diet","Dites","Cites i dites sobre alimentació");
echo "</div>";

include './includes/googleFooter.inc.php';
?>