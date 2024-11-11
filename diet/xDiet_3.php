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
    include "./includes/loader.inc.php";
?>
</head>
<body>
<?php 

//--- Settings ------------------------

$_SESSION['pageID'] = PAGE_DIET;
$sourceTable = "";
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";
//include "./includes/menu.inc.php"; 

//--- functions --------------------
 
include "./includes/dietFunctions.inc.php";
include "./includes/cards.inc.php";

//--- new content -------------------- 

//echo "<div class='dietPageContainer'>";
include "./includes/settingsEnd.inc.php";
include "./includes/menu.inc.php"; 

$user = "Cap usuari";
if (!isset($_SESSION['diet_user'])) {
    
    echo "<div class='warning'>No s'ha assignat cap usuari</div>";
    AddCard("./login/login.php", "diet", "LogIn", "Identificat per analitzar les teves dades d'ingesta", false);
//    include './includes/googleFooter.inc.php';
//    exit();
    $disabled = true;
}
else {
    $user = $_SESSION['username'];
    $user = strtoupper($user);
    $disabled = false;
}

echo "<div class='round'>Visualització en gràfiques</div>";
echo "<div class='container'>";
    AddCard("xDietUser_1.php","user","Fitxa d'usuari","Consulta de les dades biomètriques actuals de $user", false, false, false, $disabled);
    AddCard("xDietOverview.php","plot","Calories","Progrés en consum de calories", false, true, false, $disabled);
    AddCard("xDietTrace.php","plot","Pes","Evolució del pes real", false, true, false, $disabled);
echo "</div>";

include './includes/googleFooter.inc.php';
?>