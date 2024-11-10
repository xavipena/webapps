<?php
// -----------------------------
// Display a polaroid formatted image
// input 
//       value      $imgAction
//       array      $imaArray
//       IDphoto    $image
//       caption    $caption
//       menu       $photoMeta
// -----------------------------

$imgLink = "";
switch($imgAction)
{
    case "none":
        // no link from image
        $imgLink .= "          <img src='".$imgArray[0]."' width='".$imgArray[2]."' height='".$imgArray[3]."'>";
        break;

    case "update":
        // allow update metadata
        $imgLink .= "        <a href='add.php?photo=".$image."'>";
        $imgLink .= "           <img src='".$imgArray[0]."' width='".$imgArray[2]."' height='".$imgArray[3]."'>";
        $imgLink .= "        </a>";
        break;

    case "menu":
        // open popup menu
        $imgLink .= "        <a href='javascript:TogglePopup($image, \"$photoMeta\", ".DIMENSION.")'>";
        $imgLink .= "           <img src='".$imgArray[0]."' width='".$imgArray[2]."' height='".$imgArray[3]."'>";
        $imgLink .= "        </a>";
        break;
}

echo "<div class='item'>";
if ($_SESSION['polaroid'] == "Y") { 
echo "    <div class='polaroid'>";
}
echo $imgLink;
if ($_SESSION['polaroid'] == "Y") { 
echo "        <div class='caption'>";
echo "            id:".$caption;
echo "        </div>";
echo "    </div>";
}
echo "</div>";

?>