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
?>
    </head>
<body>
<?php 

//--- Settings ------------------------

$_SESSION['pageID'] = PAGE_DIET_GLOSSARY;
$sourceTable = "";
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";

//--- functions -------------------- 



//--- new content -------------------- 

$_SESSION['pageID'] = 0;
//echo "<div class='dietPageContainer'>";
include "./includes/settingsEnd.inc.php";
include "./includes/menu.inc.php"; 

$sql = "select * from diet_glossary where IDterm = ".$clean['term'];
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {

    echo "<h2>".$row['title']."</h2>";
    echo "<h3>".$row['inShort']."</h3>";
    echo "<p>".$row['description']."</p>";
    if (!empty($row['examples'])) {
        
        echo "Exemples:<br>";
        echo "<p>".$row['examples']."</p>";
    }
    $topic = $row['IDtopic'];
}

$c = 0;
echo "<h3>Referencia</h3>";
$sql = "select * from diet_glossary_links where IDterm = ".$clean['term']." order by sequence";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) {

    echo "<a href='".$row['url']." target='_blank''>".$row['name']."</a><br>";
    $c += 1;
}
if ($topic > 0) {

    $sql = "select * from diet_references where IDtopic = $topic";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        
        echo "<a href='".$row['url']." target='_blank''>".$row['name']."</a><br>".$row['description']."<br><br>";
        $c += 1;
    }
}
if ($c == 0) {

    echo "No hi ha cap referencia externa.<br>";
}
echo "<br>";
echo "<input type='button' value='  Glossary   ' onclick='location.href=\"xDietGlossary.php\"'>";
echo "&nbsp;";
echo "<input type='button' value='  < < < <   ' onclick='history.back()'>";

$page = ""; // No references link
include './includes/googleFooter.inc.php';
?>