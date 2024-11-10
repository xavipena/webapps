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
    .searchresult {
        background-color: #333;
        border: 1px solid #888;
        border-radius: 10px;
        padding: 5px;
        margin: 5px;
    }
    .green {
        color: greenyellow;
    }
</style>
</head>
<body>
<?php 
//--- Params --------------------------

$text = empty($clean['text']) ? "" : $clean['text'];
if (!empty($text)) $text = utf8_urldecode($text);

//--- Settings ------------------------

$_SESSION['pageID'] = PAGE_DIET;
$sourceTable = "";
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";

//--- functions -------------------- 

function utf8_urldecode($str) {

    return html_entity_decode(
        preg_replace(
            "/%u([0-9a-f]{3,4})/i", 
            "&#x\\1;", 
            urldecode(
                $str
            )
        ), 
        ENT_HTML5, 
        'UTF-8');
}

function NormalSearch($db, $table) {

    $text = $GLOBALS['text'];
    $sql = "select * from $table where name like '%$text%' or description like '%$text%'";
    SearchTable($db, $sql, $table);
}

function SearchTable($db, $sql, $table) {

    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        PrintRow($table, $row);
    }
}

function PrintRow($table, $row) {

    $found = "";
    $output = "";
    switch ($table) {

        case "diet":
            
            if (!empty($row['Good'])) $output .= "good: ".$row['Good']."<br>";
            if (!empty($row['Bad'])) $output .= "bad: ".$row['Bad']."<br>";
            if (!empty($row['TextGood'])) $output .= "textGood: ".$row['TextGood']."<br>";
            if (!empty($row['textBad'])) $output .= "textBad: ".$row['textBad']."<br>";
            if (!empty($row['fact'])) $output .= "facts: ".$row['facts']."<br>";
            
            if (!empty($row['reference'])) $output .= "reference: ".$row['reference']."<br>";
            break;
            
        case "diet_topics":
            
            if (!empty($row['name'])) $output .= "name: ".$row['name']."<br>";
            if (!empty($row['shortDesc'])) $output .= "shortDesc: ".$row['shortDesc']."<br>";
            if (!empty($row['remark'])) $output .= "remark: ".$row['remark']."<br>";
            break;

        case "diet_components":
        
            if (!empty($row['name'])) $output .= "name: ".$row['name']."<br>";
            if (!empty($row['description'])) $output .= "description: ".$row['description']."<br>";
            if (!empty($row['good'])) $output .= "good: ".$row['good']."<br>";
            if (!empty($row['bad'])) $output .= "bad: ".$row['bad']."<br>";
            break;

        case "diet_dishes":
        case "diet_food_groups":
        case "diet_additives":
        case "diet_products":
        case "diet_products_brand":
    
            if (!empty($row['description'])) {

                $output .= "name: ".$row['name']."<br>";
                $output .= "description: ".$row['description']."<br>";
            }
            break;

        case "diet_glossary":

            if (!empty($row['description'])) {

                $output .= "title: ".$row['title']."<br>";
                $output .= "description: ".$row['description']."<br>";
            }
            break;

        case "diet_glossary_links":

            if (!empty($row['url'])) {

                $output .= "name: ".$row['name']."<br>";
                $output .= "link: <a href='".$row['url']."'>".$row['name']."<br>";
            }
            break;

        case "diet_links":

            if (!empty($row['url'])) {

                $output .= "name: ".$row['name']."<br>";
                $output .= "description: ".$row['description']."<br>";
                $output .= "link: <a href='".$row['url']."'>".$row['name']."<br>";
            }
            break;
    }

    if ($output != "") {
        
        $found = "<span class='green'>TABLE $table:</span><br>$output";
        echo "<div class='searchresult'>$found</div>";
        $GLOBALS['results'] += 1;
    }
}

//--- new content -------------------- 

echo "<div style='width:90%; margin:auto; font-size:10pt;'>";
include "./includes/settingsEnd.inc.php";
include "./includes/menu.inc.php"; 

if (empty($text)) {

    echo "<h1>Cercador</h1>";
    echo "<div class='container'>";

    echo "<div>";
    echo "Entra un mot o text en el cercador.";
    ?>
    <form id="sform" method="post" action="xDietSearchsite.php">
        <label for="text">Text de cerca:</label>
        <input type="text" id="text" name="text" placeholder="Patatas">
        <input type="submit" value="Cerca">
    </form>
    <?php
    echo "</div>";
    echo "<div>";
    ?>
    <script async src="https://cse.google.com/cse.js?cx=647f0010463ca451b"></script>
    <div class="gcse-search"></div>
    <?php
    echo "</div>";

    echo "</div>";

    include './includes/googleFooter.inc.php';
    exit;
}

// -----------------------------------
// tables
// diet --> diet_topics
// -----------------------------------
$tlist = array();
$results = 0;

$sql = "select * from diet where Good like '%$text%' or Bad like '%$text%' or TextGood like '%$text%' or textBad like '%$text%' or facts like '%$text%'";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) {

    $sql = "select * from diet_topics where IDtopic = ".$row['IDtopic'];
    $res = mysqli_query($db, $sql);
    if ($trow = mysqli_fetch_array($res)) {
        
        PrintRow("diet_topics", $trow);
        $tlist[$results] = $row['IDtopic'];
    }
    PrintRow("diet", $row);
}

$sql = "select * from diet_topics where name like '%$text%' or shortDesc like '%$text%' or remark like '%$text%'";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) {

    $key = array_search($row['IDtopic'], $tlist);
    if ($key == 0) {

        PrintRow("diet_topics", $row);
    }
}

$table = "diet_components";
$sql = "select * from $table where name like '%$text%' or description like '%$text%' or good like '%$text%' or bad like '%$text%'";
SearchTable($db, $sql, $table);

NormalSearch($db, "diet_dishes");
NormalSearch($db, "diet_food_groups");
NormalSearch($db, "diet_additives");

$table = "diet_glossary";
$sql = "select * from $table where title like '%$text%' or description like '%$text%'";
SearchTable($db, $sql, $table);

NormalSearch($db, "diet_limits");
NormalSearch($db, "diet_links");
NormalSearch($db, "diet_products");
NormalSearch($db, "diet_product_brands");

echo $results." resultats per la cerca de '$text'<br><br>";
echo "<input type='button' value=' Nova cerca ' onclick='location.href=\"xDietSearchsite.php?text=\"'>";
include './includes/googleFooter.inc.php';
?>
