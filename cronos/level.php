<?php 
$style = "cronos";

include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";

?>
</head>
<body>
    <main>
        <div class="wrapper">
<?php 
$lang = 'ca';
$sql =  "select * from crono_cover ".
        "join crono_levels on crono_cover.IDcover = crono_levels.IDcover and crono_levels.level = 1 ".
        "where crono_cover.lang = '$lang' order by crono_cover.year desc";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $imgArray = GetPicture($row['image']);
    echo "<div class='item'>";
    echo "    <div class='polaroid'>";
    echo "        <a href='level.php?lv=1'>";
    echo "          <img src='".$imgArray[0]."'>";
    echo "        </a>";
    echo "        <div class='caption'>";
    echo "            ".$row['caption'];
    echo "        </div>";
    echo "    </div>";
    echo "</div>";
}

include "./includes/topFooter.inc.php";
?>
