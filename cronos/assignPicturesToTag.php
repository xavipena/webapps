<?php 
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";
include "./includes/paginatorStart.inc.php";
$textID = 8;

$page = $page == 0 ? 1 : $page;
$items_per_page = PER_PAGE;
$offset = ($page - 1) * $items_per_page;

$sql = "select count(*) from crono_sel_work";
$result = mysqli_query($db, $sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows / $items_per_page);

?>
<script>
    function RemoveImage(id)
    {
        DelSelected(id);
        GetStatus("galleryStats", '');
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
    <div class="wrapperCenter">
<?php 

$sql = "select * from crono_sel_work limit $offset, $items_per_page";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $image      = $row['IDphoto'];
    $imgArray   = GetPicture($image);

    echo "<div class='item'>";
    echo "  <a href='javascript:RemoveImage(".$image.")'>";
    echo "      <img src='".$imgArray[0]."' width='".$imgArray[2]."' height='".$imgArray[3]."'>";
    echo "  </a>";
    echo "</div>";
}

echo "</div>"; // wrapper close

// -----------------------------
// Tags selector
// -----------------------------
echo "<div style='$divAssignMetadata'>";
include "./includes/tags.inc.php";
echo "</div>";

// Uses params:
//      $cnt, $step, $where, $leftMenu
$cnt   = 1;
$step  = ASSIGN;
$where = "";
$leftMenu = false;
include "./includes/menuOptions.inc.php";
include "./includes/paginatorEnd.inc.php";
include "./includes/topFooter.inc.php";
?>
