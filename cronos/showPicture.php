<?php
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeaderShort.inc.php";
include "./includes/functions.inc.php";

$piwigoImageID = empty($clean['piwigo']) ? "0" : $clean['piwigo'];
$printLink = empty($clean['lnk']) ? "true" : $clean['lnk'];
$imgSize = empty($clean['size']) ? "small" : $clean['size'];

?>
</head>
<body>
<div>
<?php
if ($piwigoImageID != "0")
{
    // value      $imgAction
    // array      $imaArray
    // IDphoto    $image
    // caption    $caption
    // menu       $photoMeta

    $imgAction  = $printLink == "true" ? "update" : "none";
    $imgArray   = GetPicture($piwigoImageID, $imgSize);
    if ($imgArray != locale("strDenied")) {

        $image      = $piwigoImageID;
        $caption    = $piwigoImageID."(".$printLink.")";
        $photoMeta  = "";
        include "./includes/polaroid.inc.php";
    }
    else echo $imgArray;
}
// -----------------------------
// wrapper close
// -----------------------------
echo "</div>";

include "./includes/topFooterShort.inc.php";
?>
