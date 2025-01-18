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

function ListTedSize($id) {

    global $database;
    $sql = "select * from ted_sizes order by description";
    $result = mysqli_query($database, $sql);
    while ($row = mysqli_fetch_array($result)) {

        $selected = $id == $row['IDsize'] ? " selected" : "";
        echo "<option value='".$row['IDsize']."'$selected>".$row['description']."</option>";
    } 
}

function ListTedType($id) {

    global $database;
    $sql = "select * from ted_types order by tname";
    $result = mysqli_query($database, $sql);
    while ($row = mysqli_fetch_array($result)) {

        $selected = $id == $row['IDtype'] ? " selected" : "";
        echo "<option value='".$row['IDtype']."'$selected>".$row['tname']."</option>";
    } 
}

function ListTedStatus() {

    echo "<option value='A'>Actiu</option>";
    echo "<option value='I'>Descartat</option>";
}

function ListTedVendor($id) {

    global $database;
    $sql = "select * from ted_vendors order by name";
    $result = mysqli_query($database, $sql);
    while ($row = mysqli_fetch_array($result)) {

        $selected = $id == $row['IDvendor'] ? " selected" : "";
        echo "<option value='".$row['IDvendor']."'$selected>".$row['name']."</option>";
    } 
}

//--- new content -------------------- 

include "./includes/menu.inc.php"; 
include "./includes/settingsEnd.inc.php";

echo "<div class='container'>";

$path = "./images/";
$image = $path."ted_".$ted.".jpg";
if (!file_exists($image)) $image = $path."no_image.png";

$action = "Afegeix-lo";

$name = "";
$date = "";
$price = 0;
$size = 0;
$type = 0;
$status = "A";
$origin = "";
$vendor = 0;
$comment = "";

$sql = "select * from ted_teddies where IDted = $ted";
$result = mysqli_query($database, $sql);
if ($row = mysqli_fetch_array($result)) {

    $action = "Modifica-ho";

    $name = $row['name'];
    $date = $row['adquiredDate'];
    $price = $row['price'];
    $size = $row['IDsize'];
    $type = $row['IDtype'];
    $status = $row['status'];
    $origin = $row['origin'];
    $vendor = $row['vendor'];
    $comment = $row['remarks'];
}

$title = $name == "" ? "Teddie" : $name;
echo "<a href='$image'><img class='photo' title='$title' src='".$image."' width='200px'></a>";
?>
<form id="tedFrm" method="post" action="addNew_1.php">
    <input type ="hidden" name="tedID" value="<?php echo $ted?>">

    <div><label for="tedNom">Nom</label></div>
    <input type="text" id="tedNam" name="tedNam" value="<?php echo $name?>" required>

    <div><label for="tedDat">Data (neixement)</label></div>
    <input type="date" id="tedDat" name="tedDat" value="<?php echo $date?>">

    <div><label for="tedPri">Preu</label></div>
    <input type="number" id="tedPri" name="tedPri" value="<?php echo $price?>">

    <div><label for="tedSiz">Mida</label></div>
    <select name="tedSiz">
        <?php ListTedSize($size);?>
    </select>

    <div><label for="tedTyp">Tipus</label></div>
    <select name="tedTyp">
        <?php ListTedType($type);?>
    </select>

    <div><label for="tedSta">Estat</label></div>
    <select name="tedSta">
        <?php ListTedStatus($status);?>
    </select>

    <div><label for="tedOrg">Origen</label></div>
    <input type="text" id="tedOrg" name="tedOrg" value="<?php echo $origin?>">

    <div><label for="tedVen">Marca</label></div>
    <select name="tedVen">
        <?php ListTedVendor($vendor);?>
    </select>

    <div><label for="tedRem">Comentari</label></div>
    <textarea id="tedRem" name="tedRem" cols="40"><?php echo $comment?></textarea>
    <br><br>
    <input type="submit" value="<?php echo $action?>">
    <input type="button" value=" Cancel·la " onclick="history.back()">

</form>

<?php
echo "</div>";
// --- end content -------------------

include './includes/googleFooter.inc.php';
?>