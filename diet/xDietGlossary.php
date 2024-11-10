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
            background: linear-gradient(to left, transparent , #1f1f1f 35%), url("./images/pexels-minan1398-694740.jpg");
            background-size: cover;
        }
    </style>
</head>
<body>
<?php 

//--- Settings ------------------------

$_SESSION['pageID'] = PAGE_DIET_MENU;
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
//include "./includes/dietMenu.inc.php";


echo "<div class='container'>";
$sql = "select * from diet_glossary order by title";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    AddCard("xDietGlossary_1.php?term=".$row['IDterm'],"glossary",$row['title'],$row['inShort']);
}
echo "</div>";

include './includes/googleFooter.inc.php';
?>