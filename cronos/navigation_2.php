<?php 
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";
$textID = 7;
$dimension = "time"; // place, people
$dimension = empty($clean['dimension']) ? "time" : $clean['dimension'];


?>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="./css/wheelpicker.css">
<link rel="stylesheet" type="text/css" href="./css/hscroll.css">
<script type="text/javascript" src="./js/wheelpicker.js"></script>
<?php 
// -----------------------------
// sliders sizes
// -----------------------------
?>
<style>
    #sliderData_1, 
    #sliderData_2, 
    #sliderdata_3 {
        
        width:150px;
        height:100px;
        font-size: 22px;
        margin: 10px 10px 0px 0px;
    }
    .sliderButton {
        
        width:150px;
        height:30px;
        font-size: 20px;
        margin: 10px 10px 0px 0px;
    }
    .sliderSelect {
        
        display: none;
    }

</style>
<?php

echo "</head>";
echo "<body>";

// -----------------------------
// popup for tag selection 
// -----------------------------
include "./includes/popup.inc.php";
// -----------------------------
// loader to wait while next page loads 
// -----------------------------
include "./includes/loader.inc.php";

?>
    <main>
        <div class="wrapper">
<?php 
// -----------------------------
// Filter images based on current filters
// -----------------------------

include "./includes/movSelection2Session.inc.php";
include "./includes/selectPhotosByDim.inc.php";

$sql = "select * from crono_photos cp where cp.IDphoto > 0 $where $sortedby";
$sql .= " limit ".$_SESSION['coverpics'];
$cnt = 0;
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) {

    // value      $imgAction
    // array      $imaArray
    // IDphoto    $image
    // caption    $caption
    // menu       $photoMeta
    $imgAction  = "menu";
    $image      = $row['IDphoto'];
    $imgArray   = GetPicture($image);
    $caption    = $row['IDphoto'];

    $photoMeta  = "<table><tr><td>";
    if ($row['year'] > 0) $photoMeta .= "any: ".GetDescription($db, $row['year'], YEAR, FILTERING)[3]."<br>";
    if ($row['month'] > 0) $photoMeta .= "mes: ".GetDescription($db, $row['month'], MONTH, FILTERING)[3]."<br>";
    if ($row['country'] > 0) $photoMeta .= "país: ".GetDescription($db, $row['country'], COUNTRY, FILTERING)[3]."<br>";
    if ($row['IDcity'] > 0) $photoMeta .= "ciutat: ".GetDescription($db, $row['IDcity'], CITY, FILTERING)[3]."<br>";
    if ($row['IDevent'] > 0) $photoMeta .= "event: ".GetDescription($db, $row['IDevent'], EVENT, FILTERING)[3]."<br>";
    if ($row['IDperson'] > 0) $photoMeta .= "persona: ".GetDescription($db, $row['IDperson'], PERSON, FILTERING)[3]."<br>";
    if ($row['IDgroup'] > 0) $photoMeta .= "grup: ".GetDescription($db, $row['IDgroup'], GROUP, FILTERING)[3]."<br>";
    if ($row['IDdetail'] > 0) $photoMeta .= "detall: ".GetDescription($db, $row['IDdetail'], DETAIL, FILTERING)[3]."<br>";
    if ($row['IDtype'] > 0) $photoMeta .= "tipus: ".GetDescription($db, $row['IDtype'], TYPE, FILTERING)[3]."<br>";
    $photoMeta .= "</td><td>";
                 
    include "./includes/polaroid.inc.php";
    $cnt += 1;
}

// -----------------------------
// Params
// Uses $cnt as param
// -----------------------------
$step = NONE;
$leftMenu = false;
include "./includes/menuOptions.inc.php";

// -----------------------------
// left side list
// -----------------------------
function ListItems($db, $sql, $title) {

    $c = 0;
    $list = "";

    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        
        $list .= $row['name']."<br>";
        $c += 1;
    }
    if ($c) {

        echo "<b>$title</b><br>".$list;
    }
}

// -----------------------------
// Add where clause
// -----------------------------
$where = "where IDphoto > 0 ".$where;

echo "<div style='$divLeftList'>";
echo locale("strDimDescription")."<br>";

$sql =  "select distinct cp.IDsubject, cs.name from crono_photos cp ".
        " join crono_subjects cs on cs.IDsubject = cp.IDsubject and cs.lang = '$lang' ".
        $where;
ListItems($db, $sql, locale("strSubject"));
 
$sql =  "select distinct cp.IDdetail, cs.name from crono_photos cp ".
        " join crono_details cs on cs.IDdetail = cp.IDdetail and cs.lang = '$lang' ".
        $where;
ListItems($db, $sql, locale("strDetail"));

switch ($dimension) {

    case "time":

        $sql =  "select distinct cp.IDevent, cs.name from crono_photos cp ".
                " join crono_events cs on cs.IDevent = cp.IDevent and cs.lang = '$lang' ".
                $where;
        ListItems($db, $sql, locale("strEvent"));

        $sql =  "select distinct cp.IDperson, cs.name from crono_photos cp ".
                " join crono_persons cs on cs.IDperson = cp.IDperson ".
                $where;
        ListItems($db, $sql, locale("strPerson"));
        break;

    case "place":

        $sql =  "select distinct year as name from crono_photos cp ".
                $where." and year > 0";
        ListItems($db, $sql, locale("strYear"));

        $sql =  "select distinct cp.month, cs.name from crono_photos cp ".
                " join crono_months cs on cs.IDmonth = cp.month ".
                $where;
        ListItems($db, $sql, locale("strMonth"));

        $sql =  "select distinct cp.IDperson, cs.name from crono_photos cp ".
                " join crono_persons cs on cs.IDperson = cp.IDperson ".
                $where;        
        ListItems($db, $sql, locale("strPerson"));
        break;

    case "people":

        $sql =  "select distinct year as name from crono_photos cp ".
                $where." and year > 0";
        ListItems($db, $sql, locale("strYear"));

        $sql =  "select distinct cp.month, cs.name from crono_photos cp ".
                " join crono_months cs on cs.IDmonth = cp.month ".
                $where;
        ListItems($db, $sql, locale("strMonth"));

        $sql =  "select distinct cp.IDevent, cs.name from crono_photos cp ".
                " join crono_events cs on cs.IDevent = cp.IDevent and cs.lang = '$lang' ".
                $where;
        ListItems($db, $sql, locale("strEvent"));
        break;
}
echo "</div>";

// -----------------------------
// Filter selector
// -----------------------------
echo "<div class='filterSelection'>"; 

    echo "Selector";
    $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $_SESSION['url'] = $actual_link;
    include "./includes/filtersForNav.inc.php";

echo "</div>".PHP_EOL;

echo "<div id='filterDataNav'></div>".PHP_EOL; 

// -----------------------------
// wrapper close
// -----------------------------
echo "</div>";

echo "<div style='position:relative;left:$leftMenuSize'><ul>";
// -----------------------------
// dimension start
echo "<li><div style='width:100%'>";
// -----------------------------

echo "<div class='wrapperWheels'>";

echo "<div class='wheel' id='sliderData_1'></div>".PHP_EOL; 
echo "<div class='wheel' id='sliderData_2'></div>".PHP_EOL; 
echo "<div class='wheel' id='sliderData_3'></div>".PHP_EOL; 

echo "</div>";
echo "<div class='wrapperWheels'>";

echo "Selecciona el filtre i clica per actualitzar<br>";

echo "</div>";
echo "<div class='wrapperWheels'>";

echo "<button type='button' class='sliderButton' onclick='javascript:Go()'>Veure</button>";
echo "<button type='button' class='sliderButton' onclick='javascript:Go()'>Veure</button>";

echo "<div id='selectedType_1' class='sliderSelect'></div>".PHP_EOL; 
echo "<div id='selectedItem_1' class='sliderSelect'></div>".PHP_EOL; 
echo "<div id='selectedType_2' class='sliderSelect'></div>".PHP_EOL; 
echo "<div id='selectedItem_2' class='sliderSelect'></div>".PHP_EOL; 

echo "</div>";

// -----------------------------
// dimension end
echo "</div><span>Time</span></li>";
// -----------------------------
?>
<li> <img src="https://source.unsplash.com/TIGDsyy0TK4/500x500" alt="sliced mango. " /><span>Place</span></li>
<li> <img src="https://source.unsplash.com/TdDtTu2rv4s/500x500" alt="a bunch of blueberries. " /><span>People</span></li>
<?php
echo "</ul></div>";

// -----------------------------
// Show slider
// -----------------------------
?>
<script>
<?php
switch($dimension)
{
    case "time":

        echo "GetDates(".YEAR.",'list','sliderData_1',".$_SESSION['year'].",false,'".locale("strAll")."',".FILTERING.");";
        echo "GetDates(".MONTH.",'list','sliderData_2',".$_SESSION['month'].",false,'".locale("strAll")."',".FILTERING.");";
        break;
        
    case "place":

        $ret        = SetTableName(COUNTRY);
        $tableName  = $ret[0];
        $fieldID    = $ret[1];
        echo "GetDetails('$tableName','$fieldID','name',true,'list','sliderData_1','".$_SESSION['country']."','".locale("strAll")."',".FILTERING.");".PHP_EOL;
        $ret        = SetTableName(CITY);
        $tableName  = $ret[0];
        $fieldID    = $ret[1];
        echo "GetDetails('$tableName','$fieldID','name',true,'list','sliderData_2',".$_SESSION['city'].",'".locale("strAll")."',".FILTERING.");".PHP_EOL;
        $ret        = SetTableName(EVENT);
        $tableName  = $ret[0];
        $fieldID    = $ret[1];
        echo "GetDetails('$tableName','$fieldID','name',true,'list','sliderData_3',".$_SESSION['event'].",'".locale("strAll")."',".FILTERING.");".PHP_EOL;
        break;
        
    case "people":

        $ret        = SetTableName(PERSON);
        $tableName  = $ret[0];
        $fieldID    = $ret[1];
        echo "GetDetails('$tableName','$fieldID','name',true,'list','sliderData_1',".$_SESSION['person'].",'".locale("strAll")."',".FILTERING.");".PHP_EOL;
        $ret        = SetTableName(GROUP);
        $tableName  = $ret[0];
        $fieldID    = $ret[1];
        echo "GetDetails('$tableName','$fieldID','name',true,'list','sliderData_2',".$_SESSION['group'].",'".locale("strAll")."',".FILTERING.");".PHP_EOL;
        break;
}
?>
function Go() {

    var id = document.getElementById("selectedType_1").innerHTML;
    var val = document.getElementById("selectedItem_1").innerHTML;
    document.location.href ="./background/setValue.php?id=" + id + "&val=" + val + "&type=" + FILTERING;
}
</script>
<script>
    
    $(function() {

        // select item onload
        var ul = document.getElementById("uList" + YEAR);
        var len = ul.children.length;
        var idx = 0;
        for (var i = 0; i < len; i++ ) {

            if (ul.children[i].className == "selected") {
            
                idx = i + 1;
                document.getElementById("selectedType_1").innerHTML = YEAR;
                document.getElementById("selectedItem_1").innerHTML = ul.children[i].textContent;
                break;
            }
        }
        var ul = document.getElementById("uList" + MONTH);
        var len = ul.children.length;
        var idx = 0;
        for (var i = 0; i < len; i++ ) {
            
            if (ul.children[i].className == "selected") {
                
                idx = i + 1;
                document.getElementById("selectedType_2").innerHTML = MONTH;
                document.getElementById("selectedItem_2").innerHTML = ul.children[i].textContent;
                break;
            }
        }
        
        // define
        __SW__.selectListItemForIndex({
    
            index: i,
            wheel: 'sliderData_1',
            animate_dur: 0,
            fireEventSelect: true
        });

        // event (using addEventListener)
        const wheel_1 = document.getElementById('sliderData_1');
        wheel_1.addEventListener("select", function(e){
            
            // get selected List Item
            selectedItem = __SW__.getIndexAndValOfSelectedListItem(e.target);
            document.getElementById("selectedType_1").innerHTML = YEAR;
            document.getElementById("selectedItem_1").innerHTML = selectedItem.value;
        }); 

        __SW__.selectListItemForIndex({
            
            index: i,
            wheel: 'sliderData_2',
            animate_dur: 0,
            fireEventSelect: true
        });

        // event (using addEventListener)
        const wheel_2 = document.getElementById('sliderData_2');
        wheel_2.addEventListener("select", function(e){
            
            // get selected List Item
            selectedItem = __SW__.getIndexAndValOfSelectedListItem(e.target);
            document.getElementById("selectedType_2").innerHTML = MONTH;
            document.getElementById("selectedItem_2").innerHTML = selectedItem.value;
        }); 
    });    

<?php
echo "</script>";

include "./includes/topFooter.inc.php";
?>
