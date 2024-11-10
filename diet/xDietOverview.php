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
    include "./includes/background.inc.php";

    $week = empty($clean['week']) ? 1 : $clean['week'];
?>
<style>
    iframe {
        border: none;
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

include "./includes/cards.inc.php";

//--- new content -------------------- 

include "./includes/menu.inc.php"; 
include "./includes/settingsEnd.inc.php";

if (!isset($_SESSION['diet_user'])) {
    
    echo "<div class='warning'>No s'ha assignat cap usuari</div>";
    AddCard("./login/login.php", "diet", "LogIn", "Identificat per analitzar les teves dades d'ingesta", false);
    include './includes/googleFooter.inc.php';
    exit();
}

?>
Setmana actual
<iframe src="xDietOverviewWeek.php"
    height="350px" width="100%" allowtransparency="true">
</iframe>
Setmana anterior
<iframe src="xDietOverviewWeek.php?week=<?php echo $week?>"
    height="350px" width="100%" allowtransparency="true">
</iframe>
En el periode
<iframe src="xDietOverviewPeriod.php?week=<?php echo $week?>"
    height="350px" width="100%" allowtransparency="true">
</iframe>
<?php
$week +=1;
echo "<input type='button' value='Setmana anterior' onclick='location.href=\"xDietOverview.php?week=".$week."\"'>";

include './includes/googleFooter.inc.php';
?>