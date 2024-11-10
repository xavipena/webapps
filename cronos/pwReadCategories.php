<?php
include "./includes/dbConnect.inc.php";
include "./includes/pwConnect.inc.php"; 
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
$textID = empty($clean['lv']) ? 2 : 4;
$level = empty($clean['lv']) ? "is null" : "= ".$clean['lv'];
?>
</head>
<body>
<?php
// -----------------------------
// Navigation
// -----------------------------
$caption = "";
if (!empty($clean['lv'])) {

    $name = "";
    $_SESSION['cat_name'] .= ",".$clean['lv'];
    // get parent name
    $sql = "select name from piwigo_categories where id in (".$_SESSION['cat_name'].")";
    $result = mysqli_query($pw, $sql);
    while ($row = mysqli_fetch_array($result)) {

        $name .= " > ".$row['name'];
        $caption = $row['name'];
    }
}
else {

    $name = ">>";
    $caption = "Top albums";
    $_SESSION['cat_name'] = "0";
}
echo $name."<br><br>";

// -----------------------------
// Show data
// -----------------------------
?>
<table class="pwtable">
<caption><?php echo $caption?></caption>
<thead>
    <th>ID</th>
    <th>Àlbum</th>
    <th>Fotos</th>
    <th width='50%'><?php echo locale("strComment")?></th>
</thead>
<?php
$sql = "select id, id_uppercat, name, comment from piwigo_categories where id_uppercat ".$level." order by name";
$result = mysqli_query($pw, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    // -----------------------------
    // find if already loaded
    // -----------------------------
    $alreadyLoaded = false;
    $sql = "select * from crono_pw_albums where IDcat = ".$row['id'];
    $res = mysqli_query($db, $sql);
    if (!$w = mysqli_fetch_array($res)) {

        $album = "<a href='pwReadCategories.php?lv=".$row['id']."'>".$row['name']."</a>";
    }
    else {

        $album = "<span style='color:red'>".$row['name']."</span>";
        $alreadyLoaded = true;
    }
    $sql = "select count(*) from piwigo_image_category where category_id = ".$row['id'];
    $res = mysqli_query($pw, $sql);
    if (mysqli_fetch_array($res)[0] >  0) {

        $photos = "<a href='pwReadImages.php?cat=".$row['id']."'>".locale("strListPhotos")."</a>";
        // -----------------------------
        // no sub-albums
        // -----------------------------
        if (!$alreadyLoaded) $album = $row['name'];
    }
    else {

        // -----------------------------
        // no photos in root album
        // -----------------------------
        $photos = locale("strSubAlbum");
    }

    echo $rowStart.$row['id'].$newCol.$album.
                              $newCol.$photos.
                              $newCol.$row['comment'].
                              $rowEnd;
}
echo "</table>";

// -----------------------------
// Actions
// -----------------------------
echo "<br>";
if ($level == "is null") {

    echo "<button type='button' onclick='location.href=\"main.php\"'>".locale("strHome")."</button>";
}
else {

    echo "<button type='button' onclick='javascript:history.back()'>".locale("strGoBack")."</button>";
}

include "./includes/topFooter.inc.php";
?>