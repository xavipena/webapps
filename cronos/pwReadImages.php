<?php
include "./includes/dbConnect.inc.php";
include "./includes/pwConnect.inc.php"; 
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";
$textID = 3;
?>
<script>
    function ShowPic(imageID, imageSize)
    {
        var img = document.getElementById("photoImg");
        var parameters = "?piwigo=" + imageID + "&size=" + imageSize;
        var sDialog ="./showPicture.php";
        sDialog += parameters;
        window.frames[0].location.href = sDialog;
    }
</script>
</head>
<body>
<main>
<?php 
// -----------------------------
// loader to wait while next page loads 
// -----------------------------

include "./includes/loader.inc.php";

// -----------------------------
// popup for tag selection 
// -----------------------------
include "./includes/popup.inc.php"
?>

    <div class="wrapperCenter" style='flex-direction: column';>
<?php 
$cat = empty($clean['cat']) ? "0" : $clean['cat'];

// -----------------------------
// save callback
// -----------------------------
include "./includes/saveCallback.inc.php";

// -----------------------------
// Navigation
// -----------------------------
$name = "";
$_SESSION['cat_name'] .= ",".$clean['cat'];
// get parent name
$sql = "select name from piwigo_categories where id in (".$_SESSION['cat_name'].")";
$result = mysqli_query($pw, $sql);
while ($row = mysqli_fetch_array($result))
{
    $name .= " > ".$row['name'];
}
echo $name;

// -----------------------------
// Show data
// -----------------------------
$rows = 15;
echo "<table>";
echo $rowStart;

$c = 0;
$sql = "select * from piwigo_image_category where category_id = ".$cat;
$result = mysqli_query($pw, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    if (($c % $rows) == 0)
    {
        echo $newCol;
    }
    $c += 1;
    echo "<a href='javascript:ShowPic(".$row['image_id'].", \"xsmall\")'>".$row['image_id']."</a><br>";
}

echo $rowEnd;
echo "</table>";

echo "<div style='position:absolute; top:0px; right:100px;'>";
echo "  <iframe id='photoImg' frameborder='0' height='400px' width='400px'></iframe>";
echo "</div>";

// -----------------------------
// Actions
// -----------------------------
echo "<br><br>";
echo "<button type='button' onclick='location.href=\"./background/addPhotos.php?cat=$cat\"'>Afegeix totes</button>";
echo "<button type='button' onclick='location.href=\"pwReadCategories.php\"'>Torna enrere</button>";
echo "<button type='button' onclick='javascript:CallPage(\"$element\",\"main.php\")'>Inici</button>";

// -----------------------------
// wrapper close
// -----------------------------
echo "</div>"; 

// -----------------------------
// Tags selector
// Uses params:
// $showFilters
//  -> true / false
// -----------------------------
$showFilters = false;
echo "<div style='$divAssignMetadata'>";
    include "./includes/tags.inc.php";
echo "</div>";

// -----------------------------
// Right menu
// Uses params:
//      $cnt, $step, $where, $leftMenu
// -----------------------------
$cnt   = 1;
$step  = ASSIGN;
$where = "";
$leftMenu = false;
include "./includes/menuOptions.inc.php";
include "./includes/topFooter.inc.php";
?>