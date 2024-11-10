<?php 
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";
$textID = 7;
$dimension = "time"; // place, people
$dimension = empty($clean['dimension']) ? "time" : $clean['dimension'];

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

// -----------------------------
// List tags
// -----------------------------

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
// wrapper close
// -----------------------------
echo "</div>";

// -----------------------------
// Filter selector
// -----------------------------

echo "<div style='position:relative;left:$leftMenuSize'>";

    echo "Selector";
    $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $_SESSION['url'] = $actual_link;
    include "./includes/filtersForNav.inc.php";

echo "</div>";

echo "<div id='filterDataNav'></div>"; 

echo "<div style='position:relative;left:$leftMenuSize'>";

echo "<div id='sliderData_1'></div>"; 
echo "<div id='sliderData_2'></div>"; 
echo "<div id='sliderData_3'></div>"; 

// -----------------------------
// Show filter selectors:
// -----------------------------
?>
<script>
<?php
switch($dimension)
{
    case "time":

        echo "javascript:GetDates(".YEAR.",'block','sliderData_1',".$_SESSION['year'].",true,'".locale("strAll")."',".FILTERING.");";
        echo "javascript:GetDates(".MONTH.",'block','sliderData_2',".$_SESSION['month'].",true,'".locale("strAll")."',".FILTERING.");";
        break;
        
    case "place":

        $ret        = SetTableName(COUNTRY);
        $tableName  = $ret[0];
        $fieldID    = $ret[1];
        echo "GetDetails('$tableName','$fieldID','name',true,'list','sliderData_1','".$_SESSION['country']."','".locale("strAll")."',".FILTERING.");";
        $ret        = SetTableName(CITY);
        $tableName  = $ret[0];
        $fieldID    = $ret[1];
        echo "GetDetails('$tableName','$fieldID','name',true,'list','sliderData_2',".$_SESSION['city'].",'".locale("strAll")."',".FILTERING.");";
        $ret        = SetTableName(EVENT);
        $tableName  = $ret[0];
        $fieldID    = $ret[1];
        echo "GetDetails('$tableName','$fieldID','name',true,'list','sliderData_3',".$_SESSION['event'].",'".locale("strAll")."',".FILTERING.");";
        break;
        
    case "people":

        $ret        = SetTableName(PERSON);
        $tableName  = $ret[0];
        $fieldID    = $ret[1];
        echo "GetDetails('$tableName','$fieldID','name',true,'list','sliderData_1',".$_SESSION['person'].",'".locale("strAll")."',".FILTERING.");";
        $ret        = SetTableName(GROUP);
        $tableName  = $ret[0];
        $fieldID    = $ret[1];
        echo "GetDetails('$tableName','$fieldID','name',true,'list','sliderData_2',".$_SESSION['group'].",'".locale("strAll")."',".FILTERING.");";
        break;
}

?>
</script>

<?php
echo "</div>";

include "./includes/topFooter.inc.php";
?>
