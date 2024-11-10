<?php
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
?>
</head>
<body>
    <main>
        <div class="wrapperCenter">
            <table>
<?php
echo $rowStart.locale("strImported").$newCol.$clean['ins'].$rowEnd;
echo $rowStart.locale("strUpdated").$newCol.$clean['upd'].$rowEnd;

$sql = "select count(*) from crono_photos where IDphoto > 0";
$result = mysqli_query($db, $sql);
$cnt = mysqli_fetch_array($result)[0]; 
echo $rowStart.locale("strTotPhotos").$newCol.$cnt.$rowEnd;

$sql = "select count(*) from crono_photos where IDphoto = 0";
$result = mysqli_query($db, $sql);
$cnt = mysqli_fetch_array($result)[0]; 
echo $rowStart.locale("strTotNoID").$newCol.$cnt.$rowEnd;

$sql =  "select count(*), year, month from crono_photos ".
        "where IDphoto > 0 group by year, month";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    echo $rowStart.$row[1]."/".$row[2].":".$newCol.$row[0].$rowEnd;
}
echo "</table>";

// -----------------------------
// get callback
// -----------------------------
$url = "";
include "./includes/readCallback.inc.php";

if ($url != "") {
    
    echo "<button type='button' onclick='location.href=\"$url\"'>".locale("strContinue")."</button>";
}

// -----------------------------
// wrapper close
// -----------------------------
echo "</div>"; 

include "./includes/topFooter.inc.php";
?>