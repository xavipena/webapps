<?php
// -----------------------------
// Right Options menu
// -----------------------------

$rightCol = "<td align='right' width='65%'>";

echo "<div style='$divGalleryMenu'>"; 
echo "<b>Estats</b><br>";
echo "  <div id='galleryStats' style='width:100%;'></div>";

// WS call
echo "<script>GetStatus('galleryStats','1');</script>";

echo "<table width='100%'>";
if ($page > 0)
{
    echo "<tr>".$rightCol.locale("strPages")."</td>".$rightCol.$page." de ".$total_pages.$rowEnd;
    echo "<tr>".$rightCol.locale("strPerPage")."</td>".$rightCol.PER_PAGE.$rowEnd;
}
echo "<tr>".$rightCol.locale("strLanguage")."</td>".$rightCol.$lang.$rowEnd;
echo "</table>";
echo "<br><b>Opcions</b><br>";
if ($cnt > $_SESSION['coverpics'])
{
    echo "  <a href='javascript:CallPage(\"$element\",\"showPictures.php\")'>".locale("strSeeMore")."</a><br>";
}
switch ($step)
{
    case NONE:
        echo "  <a href='javascript:CallPage(\"$element\",\"selectPicturesToTag.php\")'>".locale("strTagPhotos")."</a><br>";
        echo "  <a href='javascript:CallPage(\"$element\",\"tagPictures.php\")'>".locale("strTagPhoto")."</a><br>";
        break;
    case SELECTION:
        if ($cnt != HIGH_VALUE)
        {
            echo "  <a href='javascript:CallPage(\"$element\",\"assignPicturesToTag.php\")'>".locale("strAssignTags")."</a><br>";
        }
        echo "  <a href='./background/removeSelected.php'>".locale("strDelSelection")."</a><br>";
        break;
    case ASSIGN:
        echo "  <a href='javascript:CallPage(\"$element\",\"savePicturesToTag.php\")'>".locale("strSaveTags")."</a><br>";
        break;
    case SAVE:
        echo "  <a href='javascript:CallPage(\"$element\",\"tagPictures.php\")'>".locale("strNextPhoto")."</a><br>";
        break;
}
if (!empty($imgArray[1]))
{
    echo "  <a target='_blank' href='".PIWIGO_GALLERIES."index.php?/category/".$imgArray[1]."'>".locale("strGoPiwigo")."</a><br>";
}

// -----------------------------
// Reload page
// -----------------------------
include __DIR__."/saveCallback.inc.php";

echo "  <a href='javascript:CallPage(\"$element\",\"$actual_link\")'>".locale("strReload")."</a><br>";

echo "<br><b>Menú de funcions</b><br>";
echo "  <a href='graph.php'>".locale("str3DGraf")."</a> <br>";
echo "  <a href='add.php'>".locale("strImportPhoto")."</a> <br>";
echo "  <a href='settings.php'>".locale("strSettings")."</a><br>";

echo "<br><b>Navegació</b><br>";
echo "  <a href='navigation.php'>".locale("strNavDimensions")."</a><br>";
echo "  <a href='main.php'>".locale("strPhotoFinder")."</a><br>";
echo "  <a href='".PIWIGO_GALLERIES."' target='_blank'>Piwigo</a><br>";

echo "<br><b>Manteniment</b><br>";
echo "  <a href='metadataMenu.php'>".locale("strMetadata")."</a> <br>";
echo "  <a href='pwReadCategories.php'>".locale("strPwAlbum")."</a> <br>";

echo "<br><b>Utils</b><br>";
echo "  <a href='maintenance/upd_photo_group.php'>".locale("strUpdPeople")."</a> <br>";
echo "  <a href='maintenance/upd_clear_all.php'>".locale("strStartOver")."</a> <br>";

echo "</div>"; // divGalleryMenu

// -----------------------------
// Filter selector
// ----------------------------- 
if ($leftMenu) {

    echo "<div style='$divFilterSelection'>";
        echo "Selector";
        include "./includes/filters.inc.php";

        if ($step == SELECTION) {

            include __DIR__."/saveCallback.inc.php";
            echo "<a href='./background/copyToTags.php'>".locale("strCopy2Tags")."</a><br>";
            echo "<a href='./background/copyToSelection.php'>".locale("strCopy2Selection")."</a>";
        }
    echo "</div>";
}

// -----------------------------
// General messages
// -----------------------------
if ($cnt == 0)
{
    echo "<div class='messages' style='$border'>";
    echo locale("strNoPhoto");
    echo "</div>";
}

?>