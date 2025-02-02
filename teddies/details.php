<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "teddies";

    include "./includes/dbConnect.inc.php";    // $db4
    include "./includes/dbConnect.inc.php";    // $db
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
</head>
<body>
<?php 
//--- Settings -----------------------

$_SESSION['pageID'] = PAGE_TEDDIES;
$sourceTable = "ted_teddies";
$database = $db4;
$ted = empty($clean['id']) ? "0" : $clean['id'];
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";

//--- functions ---------------------- 
include "./includes/functions.inc.php";

function GetTedSize($id) {

    global $database;
    $sql = "select * from ted_sizes where IDsize = $id";
    $result = mysqli_query($database, $sql);
    if ($row = mysqli_fetch_array($result)) {
    
        return $row['description'];
    } 
    else {
        
        return "<span style='color: red;'>Desconegut</span>";
    }
}

function GetTedStatus($s) {

    return $s == "A" ? "Actiu" : "Descartat";
}

function GetTedVendor($id) {

    global $database;
    $sql = "select * from ted_vendors where IDvendor = $id";
    $result = mysqli_query($database, $sql);
    if ($row = mysqli_fetch_array($result)) {
    
        return $id > 0 ? $row['name'] : "<span style='color: red;'>".$row['name']."</span>";
    } 
    else {

        return "<span style='color: red;'>Desconegut</span>";
    }
}

function GetFriends($ted) {

    global $database;
    $friendList = "";

    $sql =  "select ted_teddies.name tname, ted_friends.IDfriend friend from ted_friends ".
            "join ted_teddies on ted_friends.IDfriend = ted_teddies.IDted ".
            "where ted_friends.IDted = $ted";
    $result = mysqli_query($database, $sql);
    while ($row = mysqli_fetch_array($result)) {
    
        if (!empty($friendList)) $friendList .= ", ";   
        $friendList .= "<a href='details.php?id=".$row['friend']."'>".$row['tname']."</a>";
    } 
    if (empty($friendList)) $friendList = "Cap amic";
    return $friendList;
}

//--- new content -------------------- 

include "./includes/menu.inc.php"; 
include "./includes/settingsEnd.inc.php";

echo "<div class='container'>";

$path = "./images/";
$image = $path."ted_".$ted.".jpg";
if (!file_exists($image)) $image = $path."no_image.png";
echo "<a href='$image'><img class='photo' title='Teddie' src='".$image."' width='200px'></a>";

echo "<table class='dietcard'>";
echo "<caption>Teddies</caption>";
echo "<thead><tr>".
            "<th>Id.</th>".
            "<th>Descripció</th>".
            "<th>Comentari</th>".
    "</tr></thead>";
$sql = "select * from ted_teddies where IDted = $ted";
$result = mysqli_query($database, $sql);
$row = mysqli_fetch_array($result);
    
$links = "";
if (!empty($row['link'])) {

    $links = "<a href='".$row['link']."'>Viatge</a>";
}

$currentName = $row['name'];
echo $rowStart."Nom".$newCol.$row['name']."</td><td rowspan='4' valign='top'>".$row['remarks'].$rowEnd;
echo $rowStart."Data".$newCol.$row['adquiredDate'].$rowEnd;
echo $rowStart."Preu".$newCol.$row['price']."€ ".$rowEnd;
echo $rowStart."Mida".$newCol.GetTedSize($row['IDsize']).$rowEnd;
echo $rowStart."Tipus".$newCol.GetTedType($row['IDtype'])."<td rowspan='2' valign='top'>".$links.$rowEnd;
echo $rowStart."Estat".$newCol.GetTedStatus($row['status']).$rowEnd;
echo $rowStart."Origen".$newCol.$row['origin']."<td rowspan='2' valign='top'>Amics:<br>".GetFriends($ted).$rowEnd;
echo $rowStart."Marca".$newCol.GetTedVendor($row['vendor']).$rowEnd;

echo "</table>";

// --------------------------------
// DIP - document Identificació Peluix
// --------------------------------

teddyID($row);

// --------------------------------
// birthday
// --------------------------------

$birthDate = '1999-02-26'; // Read this from the DB instead
$time = strtotime($birthDate);
if(date('m-d') == date('m-d', $time)) {

    echo "<div class='birthday'>";
    echo "Feliç aniversari!";
    echo "</div>";
}

echo "</div>";

// --------------------------------
// prepare next
// --------------------------------

$newID = 1;
$sql = "select IDted from ted_teddies where name > '$currentName' order by name";
$result = mysqli_query($database, $sql);
if ($row2 = mysqli_fetch_array($result)) {
    
    $newID = $row2['IDted'];
}
else {
    
    $sql = "select IDted from ted_teddies order by name limit 1";
    $result = mysqli_query($database, $sql);
    $newID = mysqli_fetch_array($result)[0];
}

echo "<br><hr><br>";
echo "<div class='container'>";

echo "<input type='button' value=' Modifca-ho ' onclick='location.href=\"addNew.php?id=$ted\"'>";
echo "<input type='button' value=' Següent teddy ' onclick='location.href=\"details.php?id=$newID\"'>";
echo "<input type='button' value=' Torna al catàleg ' onclick='location.href=\"catalog.php\"'>";
if (!empty($row['link'])) {

    echo "<input type='button' value=' Aventures ' onclick='location.href=\"".$row['link']."\"'>";
}

echo "</div>";

// --- end content -------------------

include './includes/googleFooter.inc.php';
?>