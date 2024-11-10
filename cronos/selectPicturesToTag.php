<?php 
// -----------------------------
// Procedure:
// select photos -> assign tags --> save to database
// programs:
// - selectPicturesToTag.php
// - assignPicturesToTag.php
// - savePicturesToTag.php
// -----------------------------
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";
include "./includes/paginatorStart.inc.php";
$textID = 9;
?>
<script>
    function SelectImage(id, where)
    {
        AddSelected(id);
        GetStatus("galleryStats", where);
    }
</script>
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
?>
    <div class="wrapper">
<?php 

include "./includes/movSelection2Session.inc.php";
include "./includes/selectPhotos.inc.php";

$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $image      = $row['IDphoto'];
    $imgArray   = GetPicture($image);

    echo "<div class='item'>";
    echo "        <a href='javascript:SelectImage(".$image.", \"1\")'>";
    echo "           <img src='".$imgArray[0]."' width='".$imgArray[2]."' height='".$imgArray[3]."'>";
    echo "        </a>";
    echo "</div>";
}

// -----------------------------
// wrapper close
// -----------------------------
echo "</div>"; 

// call web service to update screen

// menu params
// $cnt =
$step = SELECTION;
include "./includes/menuOptions.inc.php";
include "./includes/paginatorEnd.inc.php";
include "./includes/topFooter.inc.php";
?>
