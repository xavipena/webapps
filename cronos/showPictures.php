<?php 
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";
include "./includes/paginatorStart.inc.php";
?>
</head>
<body>
    <main>
<?php 
// -----------------------------
// popup for tag selection 
// -----------------------------
include "./includes/popup.inc.php";
// -----------------------------
// loader to wait while next page loads 
// -----------------------------
include "./includes/loader.inc.php";

echo "<div class='wrapper'>";

include "./includes/movSelection2Session.inc.php";
include "./includes/selectPhotos.inc.php";
    
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

// -----------------------------
// wrapper close
// -----------------------------
echo "</div>"; 

include "./includes/menuOptions.inc.php";
include "./includes/paginatorEnd.inc.php";
include "./includes/topFooter.inc.php";
?>
