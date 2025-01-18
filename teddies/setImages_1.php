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
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";

function MountSelect($db) {

    echo "<select name='tedID'>";
    $sql = "select * from ted_teddies where status = 'A' order by name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        echo "<option value='".$row['IDted']."'>".$row['IDted']." - ".$row['name']."</option>";
    }
    echo "</select>";
}

//--- new content -------------------- 

include "./includes/menu.inc.php"; 
include "./includes/settingsEnd.inc.php";

echo "<div class='container'>";

$path = "./images/";
$img = $_GET['img'];
echo "<img class='photo' src='".$path.$img."' width='200px'>";
?>
<fieldset>
    <legend>Assigna la foto</legend>
    
    <form id="assForm" method="post" action="setImages_2.php">
        <input type="hidden" name="tedIMG" value="<?php echo $img; ?>">
        <div><label for="tedID">Al teddie</label></div>
        <?php MountSelect($database);?>
        <br><br>
        <input type="submit" value="  Assigna-la  "><br><br>
        <input type="button" value="  Nou teddie  " onclick="location.href='addNew.php'">
    </form>
</fieldset>

<?php
    $sql = "select count(*) from ted_teddies where status = 'A'";
    $result = mysqli_query($database, $sql);
    $cnt_1 = mysqli_fetch_array($result)[0];
    $cnt_2 = 0;

    $path = "./images/";
    if (file_exists($path)) {

        $files = array_slice(scandir($path), 2);
        $cnt_2 = count($files);
    }

    echo "<fieldset>";
    echo "  <legend>Llista de teddies</legend>";
    echo "$cnt_1 teddies a la llista<br>";
    echo "$cnt_2 teddies en total<br>";
    echo "</fieldset>";


echo "</div>";

// --- end content -------------------

include './includes/googleFooter.inc.php';
?>