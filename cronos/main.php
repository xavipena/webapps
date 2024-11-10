<?php 
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";
$textID = 5;
//include "./includes/description.inc.php";
?>
</head>
<body>    
<?php 
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
$lang = 'ca';
$source = empty($clean['source']) ? "cover" : "photos";

include "./includes/movSelection2Session.inc.php";
include "./includes/selectPhotos.inc.php";

// count selection
$sql = "select count(*) from crono_photos where IDphoto > 0 $where";
$result = mysqli_query($db, $sql);
$cnt = mysqli_fetch_array($result)[0];

$_SESSION['source'] = $source;

$sql = "select * from crono_photos where IDphoto > 0 $where $sortedby limit ".$_SESSION['coverpics'];
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    // value      $imgAction
    // array      $imaArray
    // IDphoto    $image
    // caption    $caption
    // menu       $photoMeta
    $imgAction  = "update";
    $image      = $row['IDphoto'];
    $imgArray   = GetPicture($image);
    $caption    = $row['IDphoto'];
    $photoMeta  = "";
    include "./includes/polaroid.inc.php";
}
echo "</div>";

// Uses $cnt as param
include "./includes/menuOptions.inc.php";
include "./includes/topFooter.inc.php";
?>
